<?php
/**
 * Контроллер для работы кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class CashController extends MPaidController
{ //TODO блокировка кнопок добавления и прочего при ajax-запросе.
	public function accessRules()
	{
		return [
			[
				'allow', //разрешить только авториз. юзерам.
				'controllers'=>['paid/cash'],
				'users'=>['@'],
			],
			[
				'deny', //запрет всем остальным и перенаправление.
				'deniedCallback'=>[$this, 'redirectToDenied'],
				'controllers'=>['paid/cash'],
			],
		];
	}
	
//	private function renderDuplicate($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts, $documentTypeListData, $genderListData)
//	{
//		$this->render('index', ['modelPatient'=>$modelPatient,
//								'modelPaid_Medcard'=>$modelPaid_Medcard,
//								'modelPatient_Documents'=>$modelPatient_Documents,
//								'modelPatient_Contacts'=>$modelPatient_Contacts,
//								'documentTypeListData'=>$documentTypeListData,
//								'genderListData'=>$genderListData,
//		]);
//	}
	
	/**
	 * ajax валидация добавления/редактирования/поиска групп/подгрупп или услуг
	 * см. CActiveForm
	 * @param object $model
	 */
	private function ajaxValidatePaidServiceGroup($modelPaid_Service_Group=null, $modelPaid_Service=null)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			if(Yii::app()->request->getPost('formAddGroup')) //совпадает с редактированием (updateGroup)
			{
				echo CActiveForm::validate($modelPaid_Service_Group);
				Yii::app()->end();
			}
			elseif(Yii::app()->request->getPost('formAddServices'))
			{
				echo CActiveForm::validate($modelPaid_Service);
				Yii::app()->end();
			}
			elseif(Yii::app()->request->getPost('formUpdateService'))
			{
				echo CActiveForm::validate($modelPaid_Service);
				Yii::app()->end();
			}
			elseif(Yii::app()->request->getPost('formSearchServices'))
			{
				echo CActiveForm::validate($modelPaid_Service);
				Yii::app()->end();
			}
		}
	}
	
	/**
	 * Отключаем уже подключенные скрипты
	 */
	public static function disableScripts()
	{
		Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
//		Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.js']=false;
	}
	
	/**
	 * Результат поиска
	 * Вызывается ajax-запросом
	 * и грузится результат в модальное окно в виде CGridView
	 */
	public function actionSearchServicesResult()
	{//TODO кнопка перейти переходит на группу, надо на услугу (подсвечивать)
		self::disableScripts();
		$modelPaid_Service=new Paid_Services('paid.cash.search');
//		$modelPaid_Service->globalSearch=true; //жесткое или нежёсткое сравнение по группам (для г
		
		$this->ajaxValidatePaidServiceGroup(null, $modelPaid_Service); //for formSearchServices
		//своего рода рекурсия, метод через ajax запрос вызывает сам себя но не попадает туда, а идёт дальше
		
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('Paid_Services', $_POST)) //afterValidate form
		{
			$modelPaid_Service->hash=substr(md5(uniqid("", true)), 0, 4);
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services', $_POST); //запрос ajax
			$this->renderPartial('gridSearchServices', ['modelPaid_Service'=>$modelPaid_Service], false, true);
		}
		elseif(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('gridSearchServices'))
		{ //обработка кнопок CGridView (ajax)
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services'); //hash тоже
			$this->renderPartial('gridSearchServices', ['modelPaid_Service'=>$modelPaid_Service]);
			Yii::app()->end();
		}
	}
	
	/**
	 * Работа с группами и услугами платного модуля.
	 * @param integer $group_id #ID группы услуг.
	 */
	public function actionGroups($group_id=null)
	{
		$modelPaid_Service=new Paid_Services;
		$searchModelPaid_Service=new Paid_Services;
		
		if(isset($group_id))
		{
			$record=Paid_Service_Groups::model()->findByPk($group_id);
			if($record===null)
			{
				throw new CHttpException(404, 'Такой группы не существует!');
			}
			$modelPaid_Service->paid_service_group_id=$group_id; //выбор услуг данной группы.
			
			if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('gridSearchGroupServices'))
			{
				$this->render('Groups', ['modelPaid_Service'=>$modelPaid_Service]);
			}
		}
		$this->render('Groups', ['modelPaid_Service'=>$modelPaid_Service, 'searchModelPaid_Service'=>$searchModelPaid_Service]);
	}
	
	/**
	 * Добавление группы или подгруппы из модали.
	 * Подгрузка метода через ajax-запрос (paid.js)
	 * @param integer $group_id #ID группы или подгруппы. По умолчанию: 0, т.к. это самые главные группы.
	 */
	public function actionAddGroup($group_id=0)
	{
		self::disableScripts();
		$modelPaid_Service_Group=new Paid_Service_Groups('paid.cash.create');
		$modelPaid_Service_Group->p_id=$group_id;
		$modelDoctors=new Doctors;
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Service_Groups'))//после ajax валидации CActiveForm отправляет submit на форму
		{
			$modelPaid_Service_Group->attributes=Yii::app()->request->getPost('Paid_Service_Groups');
			$transaction=Yii::app()->db->beginTransaction();
			try
			{
				if($modelPaid_Service_Group->save()) 
				{
					is_array(Yii::app()->request->getPost('Doctors')['id']) ? Yii::app()->request->getPost('Doctors')['id'] : [];
					foreach(Yii::app()->request->getPost('Doctors')['id'] as $doctor_id)
					{
						$modelPaid_Services_Doctors=new Paid_Services_Doctors('paid.cash.create');
						$modelPaid_Services_Doctors->paid_service_group_id=Yii::app()->db->getLastInsertID('paid.paid_service_groups_paid_service_group_id_seq');
						$modelPaid_Services_Doctors->doctor_id=$doctor_id;
						if(!$modelPaid_Services_Doctors->save())
						{
							$transaction->rollback();
							throw new CHttpException(404, 'Ошибка в запросе БД');
						}
						unset($modelPaid_Service_Doctors);
					}
					$transaction->commit();
					$this->redirect(['cash/groups', 'group_id'=>Yii::app()->db->getLastInsertID('paid.paid_service_groups_paid_service_group_id_seq')]);
				}
				else
				{
					$transaction->rollback();
					Yii::app()->end();
				}
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
		}
		$this->renderPartial('addGroupForm', ['modelPaid_Service_Group'=>$modelPaid_Service_Group, 'modelDoctors'=>$modelDoctors], false, true);
	}
	
	/**
	 * Добавление услуги из модали.
	 * Подгрузка метода через ajax-запрос (paid.js)
	 * @param integer $group_id #ID группы, в которую будем добавлять услугу по умолчанию.
	 */
	public function actionAddService($group_id)
	{
		self::disableScripts();
		$modelPaid_Service_Group=new Paid_Service_Groups('paid.cash.create');
		$modelPaid_Service=new Paid_Services('paid.cash.create');

		if(isset($group_id))
		{ //ловим ошибку
			$record=Paid_Service_Groups::model()->findByPk($group_id);
			if($record===null)
			{
				throw new CHttpException(404, 'Такой группы не существует!');
			}
		}
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group, $modelPaid_Service); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Services'))
		{
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
			$modelPaid_Service->price=ParseMoney::encodeMoney($modelPaid_Service->price); //преобразуем к деньгам (умножаем на 100)
			if($modelPaid_Service->save())
			{
				$this->redirect(Yii::app()->request->urlReferrer);
			}
		}
		$this->renderPartial('addServiceForm', ['modelPaid_Service'=>$modelPaid_Service], false, true);
	}
	
	/**
	 * Редактирование услуги.
	 * Подгрузка метода через ajax-запрос (paid.js)
	 * @param int $id #ID услуги
	 */
	public function actionUpdateService($id)
	{
		self::disableScripts();
		$modelPaid_Service=Paid_Services::model()->findByPk($id);
		$serviceGroupsListData=Paid_Service_Groups::getServiceGroupsListData(null);
		
		if($modelPaid_Service===null)
		{
			echo 'Такой услуги не существует!';
			Yii::app()->end();
		}
		
		$modelPaid_Service->setScenario('paid.cash.update');
		$modelPaid_Service->price=ParseMoney::decodeMoney($modelPaid_Service->price); //преобразуем к деньгам (делим на 100)
		$modelPaid_Service->since_date=Yii::app()->dateFormatter->format('yyyy-MM-dd', $modelPaid_Service->since_date);
		$modelPaid_Service->exp_date=Yii::app()->dateFormatter->format('yyyy-MM-dd', $modelPaid_Service->exp_date);
		
		$this->ajaxValidatePaidServiceGroup(null, $modelPaid_Service); // валидируем CActiveFrom ajax
		
		if(Yii::app()->request->getPost('Paid_Services'))
		{
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
			$modelPaid_Service->price=ParseMoney::encodeMoney($modelPaid_Service->price); //преобразуем к деньгам (умножаем на 100)
			$modelPaid_Service->save();
			$this->redirect(['cash/groups', 'group_id'=>$modelPaid_Service->paid_service_group_id]);
		}
		
		$this->renderPartial('updateServiceForm', ['modelPaid_Service'=>$modelPaid_Service, 'serviceGroupsListData'=>$serviceGroupsListData], false, true);
	}
	
	/**
	 * Редактирование группы.
	 * Подгрузка метода через ajax-запрос (paid.js)
	 * @param integer $group_id #ID группы
	 * @throws CHttpException
	 */
	public function actionUpdateGroup($group_id)
	{//TODO!!!CRITICAL BUG: при изменении группы у родителя с p_id=0 исчезает вся ветка. добавить проверку
		self::disableScripts();
		$modelPaid_Service_Group=Paid_Service_Groups::model()->findByPk($group_id);
		$serviceGroupsListData=Paid_Service_Groups::getServiceGroupsListData($group_id);
		
		if($modelPaid_Service_Group===null)
		{
			throw new CHttpException(404, 'Такой группы не существует!');
		}
		
		$modelPaid_Service_Group->setScenario('paid.cash.update');
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Service_Groups'))//после ajax валидации CActiveForm отправляет submit на форму
		{
			$modelPaid_Service_Group->attributes=Yii::app()->request->getPost('Paid_Service_Groups');
			
			if($modelPaid_Service_Group->save()) 
			{
				$this->redirect(['cash/groups', 'group_id'=>$group_id]);
			}
		}
		$this->renderPartial('updateGroupForm', ['modelPaid_Service_Group'=>$modelPaid_Service_Group, 'serviceGroupsListData'=>$serviceGroupsListData], false, true);
	}
	
	/**
	 * Удаление группы или подгруппы
	 * @param integer $group_id #ID группы
	 */
	public function actionDeleteGroup($group_id=null)
	{
		$recordPaid_Service_Group=Paid_Service_Groups::model()->findByPk($group_id);
		if($recordPaid_Service_Group===null)
		{
			throw new CHttpException(404, 'Такой группы не существует!');
		}
		Paid_Service_Groups::recursDeleteGroups($group_id);
	}
	
	/**
	 * Удаление услуг или одной услуги у группы
	 * @param int $id #ID услуги
	 */
	public function actionDeleteService($id=null)
	{
		if(isset($id))
		{ //выбрали одну услугу
			
			$recordPaid_Service=Paid_Services::model()->findByPk($id);
		
			if($recordPaid_Service===null)
			{
				throw new CHttpException(404, 'Такой услуги не существует!');
			}
			$recordPaid_Service->deleteByPk($id);
			Yii::app()->end();
		}
	}
	
	/**
	 * Выбрали пациента.
	 * @param int $patient_id #ID пациента
	 */
	public function actionPatient($patient_id)
	{
		$modelPatient=Patients::model()->findByPk($patient_id);
		$modelPaid_Service=new Paid_Services('paid.cash.search');
		
		if($modelPatient===null)
		{
			throw new CHttpException(404, 'Такого пациента не существует!');
		}
		$this->render('patient', ['modelPatient'=>$modelPatient, 'modelPaid_Service'=>$modelPaid_Service]);
//		if(isset($patient_id))
//		{//выбрали юзера не(!!) ajax запросом
//			$recordPatient_Documents=Patient_Documents::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
//			$recordPatient_Contacts=Patient_Contacts::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
//			$modelPatient_Documents=isset($recordPatient_Documents) ? $recordPatient_Documents : $modelPatient_Documents;
//			$modelPatient_Contacts=isset($recordPatient_Contacts) ? $recordPatient_Contacts : $modelPatient_Contacts;
//			$modelPatient=Patients::model()->findByPk($patient_id);
//			$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
//			
//			if($modelPatient===null)
//			{ //нет такого пациента
//				throw new CHttpException(404, 'Пациент на найден!');
//			}
//			elseif($recordPaid_Medcard===null)
//			{ //нет медкарты у пациента, нужно создать
//				$modelPaid_Medcard=new Paid_Medcards('paid.medcard.create');
//				$modelPaid_Medcard->paid_medcard_number=uniqid('', true); //TODO временно
//				$modelPaid_Medcard->enterprise_id=null;
//				$modelPaid_Medcard->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
//				$modelPaid_Medcard->patient_id=$patient_id;
//				$modelPaid_Medcard->save();
//			}
//			elseif($recordPaid_Medcard!==null)
//			{ //уже есть ЭМК платных услуг
//				$modelPaid_Medcard=$recordPaid_Medcard;
//			}
//			$this->render('patient', ['modelPatient'=>$modelPatient, 'modelPaid_Medcard'=>$modelPaid_Medcard]);
//		}
	}
	
	private function ajaxValidatePatients($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			if(Yii::app()->request->getPost('formSearchPatients'))
			{
				$validatePatient=CJSON::decode(CActiveForm::validate($modelPatient));
				$validateMedcard=CJSON::decode(CActiveForm::validate($modelPaid_Medcard));
				$validatePatient_Documents=CJSON::decode(CActiveForm::validate($modelPatient_Documents));
				$validatePatient_Contacts=CJSON::decode(CActiveForm::validate($modelPatient_Contacts));
				$arrAllErrors=array_merge($validatePatient, $validateMedcard, $validatePatient_Documents, $validatePatient_Contacts);
				
				$modelPatient_Documents_ArrTypes=isset(Yii::app()->request->getPost('Patient_Documents')['typeArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['typeArrMass'] : [];
				$modelPatient_Documents_ArrSeries=isset(Yii::app()->request->getPost('Patient_Documents')['serieArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['serieArrMass'] : [];
				$modelPatient_Documents_ArrNumbers=isset(Yii::app()->request->getPost('Patient_Documents')['numberArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['numberArrMass'] : [];
				$modelPatient_Contacts_Arr=isset(Yii::app()->request->getPost('Patient_Contacts')['valueArrMass']) ? Yii::app()->request->getPost('Patient_Contacts')['valueArrMass'] : [];
				
				foreach($modelPatient_Contacts_Arr as $contact)
				{
					$modelPatient_Contacts->value=$contact;
					$arrAllErrors=array_merge($arrAllErrors, CJSON::decode(CActiveForm::validate($modelPatient_Contacts, NULL, false)));
				}
				
				foreach($modelPatient_Documents_ArrTypes as $key=>$value)
				{
					$modelPatient_Documents->type=$value; //or $modelPatient_Documents_ArrTypes[$key];
					$modelPatient_Documents->serie=$modelPatient_Documents_ArrSeries[$key];
					$modelPatient_Documents->number=$modelPatient_Documents_ArrNumbers[$key];
					$arrAllErrors=array_merge($arrAllErrors, CJSON::decode(CActiveForm::validate($modelPatient_Documents, NULL, false)));
				}
				
				echo CJSON::encode($arrAllErrors);
				Yii::app()->end();
			}
		}
	}
	
	/**
	 * Основное рабочее место кассира
	 */
	public function actionMain()
	{
		$modelPatient=new Patients('paid.cash.search');
		$modelPaid_Medcard=new Paid_Medcards('paid.cash.search');
		$modelPatient_Contacts=new Patient_Contacts('paid.cash.search');
		$modelPatient_Documents=new Patient_Documents('paid.cash.search');	
		
		if(Yii::app()->request->getPost('create'))
		{ //меняем сценарии если нажата кнопка "сохранить"
			$modelPatient=new Patients('paid.cash.create');
			$modelPaid_Medcard=new Paid_Medcards('paid.cash.create');
			$modelPatient_Contacts=new Patient_Contacts('paid.cash.create');
			$modelPatient_Documents=new Patient_Documents('paid.cash.create');			
		}
		
		$this->ajaxValidatePatients($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts);
		
		$this->render('main', ['modelPatient'=>$modelPatient,
							   'modelPatient_Documents'=>$modelPatient_Documents,
							   'modelPatient_Contacts'=>$modelPatient_Contacts,
							   'modelPaid_Medcard'=>$modelPaid_Medcard,
		]);
	}
	
	/**
	 * Создание пациента
	 * К экшену обращение идёт после того, как прошла ajax-валидация из экшна main(), смотри afterValidate() в CActiveForm
	 */
	public function actionCreatePatient()
	{ //сюда попадают уже свалидированные данные из экшна main(), валидируем только на уровне PHP
		$modelPatient=new Patients('paid.cash.create');
		$modelPaid_Medcard=new Paid_Medcards('paid.cash.create');
		$modelPatient_Contacts=new Patient_Contacts('paid.cash.create');
		$modelPatient_Documents=new Patient_Documents('paid.cash.create');
		
		if(Yii::app()->request->getPost('Patients') && Yii::app()->request->getPost('Paid_Medcards')
		&& Yii::app()->request->getPost('Patient_Contacts') && Yii::app()->request->getPost('Patient_Documents'))
		{
			$modelPatient->attributes=Yii::app()->request->getPost('Patients');
			$modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
			$modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
			$modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
			$modelPatient->create_timestamp=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
			$modelPaid_Medcard->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
			
			$transaction=Yii::app()->db->beginTransaction();
			try
			{
				/**
				 * В случае успеха отдаем результат на клиента.
				 * 1 - значит успешное сохранение.
				 * 0 - возникли ошибки при сохранении данных в БД (не прошли валидацию и прочее).
				 */
				if(!$modelPatient->save())
				{ //не сохранился пациент, откатываем транзакцию и выводим ошибку на клиента (0)
					$transaction->rollback();
					echo 0;
					Yii::app()->end();
				}
				
				$modelPaid_Medcard->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
				$modelPatient_Contacts->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
				$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
				
				if(!($modelPaid_Medcard->save() && $modelPatient_Contacts->save() && $modelPatient_Documents->save()))
				{//запросы с ошибками, откатываем транзакцию и выводим ошибку на клиента (0)
					$transaction->rollback();
					echo 0;
					Yii:app()->end();
				}
				
				$modelPatient_Documents_ArrTypes=isset(Yii::app()->request->getPost('Patient_Documents')['typeArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['typeArrMass'] : [];
				$modelPatient_Documents_ArrSeries=isset(Yii::app()->request->getPost('Patient_Documents')['serieArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['serieArrMass'] : [];
				$modelPatient_Documents_ArrNumbers=isset(Yii::app()->request->getPost('Patient_Documents')['numberArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['numberArrMass'] : [];
				$modelPatient_Contacts_Arr=isset(Yii::app()->request->getPost('Patient_Contacts')['valueArrMass']) ? Yii::app()->request->getPost('Patient_Contacts')['valueArrMass'] : [];
				
				foreach($modelPatient_Contacts_Arr as $contact)
				{
					$modelPatient_Contacts=new Patient_Contacts('paid.cash.create');
					$modelPatient_Contacts->value=$contact;
					$modelPatient_Contacts->type=1;
					$modelPatient_Contacts->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
					
					if(!$modelPatient_Contacts->save())
					{
						$transaction->rollback();
						echo 0;
						Yii::app()->end();
					}
				}
				
				foreach($modelPatient_Documents_ArrTypes as $key=>$document)
				{
					$modelPatient_Documents=new Patient_Documents('paid.cash.create');
					$modelPatient_Documents->type=$document; //or $modelPatient_Documents_ArrTypes[$key];
					$modelPatient_Documents->serie=$modelPatient_Documents_ArrSeries[$key];
					$modelPatient_Documents->number=$modelPatient_Documents_ArrNumbers[$key];
					$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
					
					if(!$modelPatient_Documents->save())
					{
						$transaction->rollback();
						echo 0;
						Yii::app()->end();
					}
				}
				
				$transaction->commit();
				echo Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq'); //выводим patient_id и перенаправляем аяксом на acitonPatient()
				Yii::app()->end();
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
		}
	}
	
	/**
	 * Результат поиска пациента. Выводит CGridView в модальное окно. 
	 * Вызывается этот экш с main-представления ajax запросом после успешной валидации CActiveForm.
	 */
	public function actionSearchPatientsResult()
	{ //обработка всех кнопок грида попадает так же сюда, т.к. ajax запрос был послан сюда и отсюда был вызван грид
		self::disableScripts();
		$modelPatient=new Patients('paid.cash.search');
		$modelPatient->modelPaid_Medcard=new Paid_Medcards('paid.cash.search');
		$modelPatient->modelPatient_Contacts=new Patient_Contacts('paid.cash.search');
		$modelPatient->modelPatient_Documents=new Patient_Documents('paid.cash.search');
		
		$modelPatient->attributes=Yii::app()->request->getPost('Patients');
		$modelPatient->modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
		$modelPatient->modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
		$modelPatient->modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
		
		if(!Yii::app()->request->getParam('gridSearchPatients'))
		{ //первый заход в этот экшн
			$modelPatient->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		if($modelPatient->validate()
		&& $modelPatient->modelPaid_Medcard->validate()
		&& $modelPatient->modelPatient_Contacts->validate()
		&& $modelPatient->modelPatient_Documents->validate())
		{
			$this->renderPartial('gridSearchPatients', ['modelPatient'=>$modelPatient], false, true);
			Yii::app()->end();
		}
		else
		{ //TODO что-нибудь другое
			echo 'Ошибки в валидации. При возникновении данной ошибки требуется уведомить администратора ресурса!';
			Yii::app()->end();
		}
	}
	
	/**
	 * Основной экш кассы.
	 */
	public function actionIndex()
	{
		return $this->actionMain();
	}
}