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
	{ //TODO кнопка перейти переходит на группу, надо на услугу (подсвечивать)
		self::disableScripts();
		Yii::app()->clientScript->scriptMap['jquery.yiigridview.js']=false; //уже подключен, т.к. на странице присутствует GridView
		$modelPaid_Service=new Paid_Services('paid.cash.search');
		
		$this->ajaxValidatePaidServiceGroup(null, $modelPaid_Service); //for formSearchServices
		//своего рода рекурсия, метод через ajax запрос вызывает сам себя но не попадает туда, а идёт дальше
		
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('Paid_Services', $_POST)) //afterValidate form
		{
			$modelPaid_Service->hash=substr(md5(uniqid("", true)), 0, 4);
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services', $_POST); //запрос ajax
			$this->renderPartial('gridSearchServices', ['modelPaid_Service'=>$modelPaid_Service], false, true);
		}
//		elseif(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('gridSearchServices'))
//		{ //обработка кнопок CGridView (ajax)
//			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services'); //hash тоже
//			$this->renderPartial('gridSearchServices', ['modelPaid_Service'=>$modelPaid_Service]);
//			Yii::app()->end();
//		}
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
					$doctorsArray=is_array(Yii::app()->request->getPost('Doctors')['id']) ? Yii::app()->request->getPost('Doctors')['id'] : [];
					foreach($doctorsArray as $doctor_id)
					{
						$modelPaid_Services_Doctors=new Paid_Services_Doctors('paid.cash.create');
						$modelPaid_Services_Doctors->paid_service_group_id=Yii::app()->db->getLastInsertID('paid.paid_service_groups_paid_service_group_id_seq');
						$modelPaid_Services_Doctors->doctor_id=$doctor_id;
						if(!$modelPaid_Services_Doctors->save())
						{
//							$transaction->rollback();
							throw new CHttpException(404, 'Ошибка в запросе БД');
						}
						unset($modelPaid_Service_Doctors);
					}
					$transaction->commit();
					$this->redirect(['cash/groups', 'group_id'=>Yii::app()->db->getLastInsertID('paid.paid_service_groups_paid_service_group_id_seq')]);
				}
				else
				{
					throw new CHttpException(404, 'Ошибка в запросе БД');
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
		$modelPaid_Service->paid_service_group_id=$group_id;
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group, $modelPaid_Service); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Services'))
		{
			$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
			$modelPaid_Service->price=ParseMoney::encodeMoney($modelPaid_Service->price); //преобразуем к деньгам (умножаем на 100)
			if($modelPaid_Service->save())
			{
				$this->redirect(['cash/groups', 'group_id'=>$modelPaid_Service->paid_service_group_id]);
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
	{//TODO CRITICAL BUG: при изменении группы у родителя с p_id=0 исчезает вся ветка. добавить проверку
		//TODO UNIQUE (paid_service_group_id, doctor_id) in Paid_service_doctors table.
		self::disableScripts();
		$modelPaid_Service_Group=Paid_Service_Groups::model()->findByPk($group_id);
		if($modelPaid_Service_Group===null)
		{
			throw new CHttpException(404, 'Такой группы не существует!');
		}
		
		$criteria=new CDbCriteria;
		$criteria->select='doctor_id';
		$criteria->condition='paid_service_group_id=:group_id';
		$criteria->params=[':group_id'=>$group_id];
		$idDoctors=Paid_services_Doctors::model()->findAll($criteria); //находим всех докторов у данной группы.
		
		$i=0;
		$modelDoctors=new Doctors;
		foreach($idDoctors as $doctor)
		{ //вставляем в модель и выводим выделенные чекбоксы.
			$modelDoctors->id[$i]=$doctor->doctor_id;
			$i++;
		}
		
		$serviceGroupsListData=Paid_Service_Groups::getServiceGroupsListData($group_id);
		
		$modelPaid_Service_Group->setScenario('paid.cash.update');
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Service_Groups'))//после ajax валидации CActiveForm отправляет submit на форму
		{
			$modelPaid_Service_Group->attributes=Yii::app()->request->getPost('Paid_Service_Groups');
			$transaction=Yii::app()->db->beginTransaction();
			try
			{
				if($modelPaid_Service_Group->save()) 
				{
					Paid_Services_Doctors::model()->deleteAll('paid_service_group_id=:group_id', [':group_id'=>$group_id]);
					
					$doctorsArray=is_array(Yii::app()->request->getPost('Doctors')['id']) ? Yii::app()->request->getPost('Doctors')['id'] : [];
					foreach($doctorsArray as $doctor_id)
					{
						$modelPaid_Services_Doctors=new Paid_Services_Doctors('paid.cash.create');
						$modelPaid_Services_Doctors->paid_service_group_id=$group_id;
						$modelPaid_Services_Doctors->doctor_id=$doctor_id;
						if(!$modelPaid_Services_Doctors->save()) //в идеале TODO ajax-валидацию...
						{
//							$transaction->rollback();
							throw new CHttpException(404, 'Ошибка в запросе БД');
						}
						unset($modelPaid_Service_Doctors);
					}
					$transaction->commit();
					$this->redirect(['cash/groups', 'group_id'=>$group_id]);
				}
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
		}
		$this->renderPartial('updateGroupForm', ['modelDoctors'=>$modelDoctors, 'modelPaid_Service_Group'=>$modelPaid_Service_Group, 'serviceGroupsListData'=>$serviceGroupsListData], false, true);
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
		
		$transaction=Yii::app()->db->beginTransaction();
		try 
		{
			Paid_Service_Groups::recursDeleteGroups($group_id);
			if(Paid_Service_Groups::$error_delete_group==1) //нет ошибок
			{
				$transaction->commit();
				Yii::app()->end("1");
			}
			throw new CHttpException(404);
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			Yii::app()->end("0");
		}
	}
	
	/**
	 * Удаление услуг или одной услуги у группы
	 * @param int $id #ID услуги
	 */
	public function actionDeleteService($id)
	{
		$recordPaid_Service=Paid_Services::model()->findByPk($id);

		if($recordPaid_Service===null)
		{
			throw new CHttpException(404, 'Такой услуги не существует!');
		}
		$recordPaid_Order_Details=Paid_Order_Details::model()->find('paid_service_id=:id', [':id'=>$id]);
		$recordPaid_Referrals_Details=Paid_Referrals_Details::model()->find('paid_service_id=:id', [':id'=>$id]);
		
		if(isset($recordPaid_Order_Details) || isset($recordPaid_Referrals_Details))
		{ //если у группы есть связи, то удаление невозможно
			Yii::app()->end("0");
		}
		$recordPaid_Service->deleteByPk($id);
		Yii::app()->end("1");
	}
	
	/**
	 * Выбрали пациента.
	 * @param int $patient_id #ID пациента
	 */
	public function actionPatient($patient_id)
	{
//		$count = 2000000;
//		for($i=0; $i<=$count; $i++)
//		{
//			$patients=new Patients();
//			$patients->first_name =  'Тест' . $i;
//			$patients->middle_name =  'Тестович' . $i;
//			$patients->last_name =  'Тестов' .$i;
//			$patients->gender = rand(0, 1);
//			$patients->birthday=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
//			$patients->create_timestamp = Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
//			
//			$patients->save(false);
//			$patients->isNewRecord=true;
//			$idPatient = Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
//			
//			$document=new Patient_Documents();
//			$document->serie = $i;
//			$document->number = $i;
//			$document->type = $i;
//			$document->patient_id = $idPatient;
//			$document->save();
//			$document->isNewRecord=true;
//			
//			$contact = new Patient_Contacts();
//			$contact->patient_id = $idPatient;
//			$contact->type = $i;
//			$contact->value = $i . $i;
//			$contact->save();
//			$contact->isNewRecord = true;
//		}
		
		Yii::app()->clientScript->registerPackage('updatePatient');
		$modelPatient=Patients::model()->findByPk($patient_id);
		
		if($modelPatient===null)
		{
			throw new CHttpException(404, 'Такого пациента не существует!');
		}
		
		$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', ['patient_id'=>$patient_id]);

		if($recordPaid_Medcard===null)
		{ //у пользователя нет ЭМК платных услуг
			$modelPaid_Medcard=new Paid_Medcards('paid.cash.create');
			$modelPaid_Medcard->patient_id=$patient_id;
			$modelPaid_Medcard->paid_medcard_number=Paid_Orders::generateRandNumber(); //временно
			
			$modelPaid_Medcard->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
			
			if(!$modelPaid_Medcard->save())
			{
				throw new CHttpException(404, 'Запрос на создание ЭМК не был выполнен. Перезагрузите страницу (кнопка F5 на клавиатуре).');
			}
		}
		
		$recordPatient_Contacts=Patient_Contacts::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
		
		/**
		 * сохраняем юзеру хотя бы один контакт (пустой). Нужно для редактирования.
		 */
		if($recordPatient_Contacts===null)
		{
			$recordPatient_Contacts=new Patient_Contacts();
			$recordPatient_Contacts->value='';
			$recordPatient_Contacts->type=Patient_Contacts::TYPE;
			$recordPatient_Contacts->patient_id=$patient_id;
			$recordPatient_Contacts->save();
		}
		
		$recordPatient_Documents=Patient_Documents::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
		
		if($recordPatient_Documents===null)
		{
			$recordPatient_Documents=new Patient_Documents();
			$recordPatient_Documents->serie='';
			$recordPatient_Documents->number='';
			$recordPatient_Documents->type=Patients::DOCUMENT_TYPE_PASSPORT_ID;
			$recordPatient_Documents->patient_id=$patient_id;
			$recordPatient_Documents->save();
		}
		
		$this->render('patient', ['modelPatient'=>$modelPatient]);	
	}
	
	/**
	 * ajax validation for CActiveForm
	 */
	private function ajaxValidateUpdatePatient($modelPatient)
	{
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('formUpdatePatient'))
		{
			$errorsPatient=CJSON::decode(CActiveForm::validate($modelPatient));
			$errorsAll=$errorsPatient;
			$Patient_Contacts=Yii::app()->request->getPost('Patient_Contacts')!==null ? Yii::app()->request->getPost('Patient_Contacts') : array();
			$Patient_Documents=Yii::app()->request->getPost('Patient_Documents')!==null ? Yii::app()->request->getPost('Patient_Documents') : array();
			
			foreach($Patient_Contacts as $key=>$contact)
			{
				if($key==='value')
				{
					continue;
				}
				$modelPatient_Contacts=new Patient_Contacts('paid.cash.create'); // передача по ссылке
				$modelPatient_Contacts->value=$contact;
				$modelPatient_Contacts->type=1; //сомнительный параметр
				$errorsPatient_Contacts=CJSON::decode(CActiveForm::validate($modelPatient_Contacts, null, false));
				$errorsAll=array_merge($errorsAll, $errorsPatient_Contacts);
			}
			
			$i=0;
			foreach($Patient_Documents['type'] as $document)
			{
				$modelPatient_Documents=new Patient_Documents('paid.cash.create'); // передача по ссылке
				$modelPatient_Documents->type=$Patient_Documents['type'][$i];
				$modelPatient_Documents->serie=$Patient_Documents['serie'][$i];
				$modelPatient_Documents->number=$Patient_Documents['number'][$i];
				$errorsPatient_Documents=CJSON::decode(CActiveForm::validate($modelPatient_Documents, null, false));
				$errorsAll=array_merge($errorsAll, $errorsPatient_Documents);
				$i++;
			}
			echo CJSON::encode($errorsAll);
			Yii::app()->end();
		}
	}
	
	/**
	 * Редактирование пациента
	 * @param integer $patient_id
	 */
	public function actionUpdatePatient($patient_id)
	{
		$recordPatient=Patients::model()->findByPk($patient_id);
		$recordPatient->setScenario('paid.cash.update');
		$modelPatient_Document=new Patient_Documents('paid.cash.create'); // для ajax-валидации
		$modelPatient_Contact=new Patient_Contacts('paid.cash.create'); // для ajax-валидации
		
		if($recordPatient===null)
		{
			throw new CHttpException(404, 'Такого пациента не существует.');
		}
		
		$this->ajaxValidateUpdatePatient($recordPatient);
		
		/**
		 * Отправка submit() после ajax-валидации CActiveForm
		 */
		if(!Yii::app()->request->isAjaxRequest
		&& Yii::app()->request->getPost('Patients')
		&& Yii::app()->request->getPost('Patient_Contacts')
		&& Yii::app()->request->getPost('Patient_Documents'))
		{
			$recordPatient->attributes=Yii::app()->request->getPost('Patients');
			$Patient_Contacts=Yii::app()->request->getPost('Patient_Contacts')!==null ? Yii::app()->request->getPost('Patient_Contacts') : array();
			$Patient_Documents=Yii::app()->request->getPost('Patient_Documents')!==null ? Yii::app()->request->getPost('Patient_Documents') : array();
			
			$recordPatient->save();
			Patient_Contacts::updateContacts($patient_id, $Patient_Contacts);
			Patient_Documents::updateDocuments($patient_id, $Patient_Documents);
			$this->redirect(['cash/patient', 'patient_id'=>$patient_id]);
		}
		
		/**
		 * Только ajax запросы (загрузка формы из модали)
		 */
		if(Yii::app()->request->isAjaxRequest)
		{
			self::disableScripts();
			
			$recordPatient_Contact=Patient_Contacts::model()->findAll('patient_id=:patient_id', [':patient_id'=>$patient_id]);
			$recordPatient_Document=Patient_Documents::model()->findAll('patient_id=:patient_id', [':patient_id'=>$patient_id]);
			
			$this->renderPartial('updatePatientForm', ['recordPatient'=>$recordPatient,
												   'recordPatient_Contact'=>$recordPatient_Contact,
												   'recordPatient_Document'=>$recordPatient_Document,
												   'modelPatient_Document'=>$modelPatient_Document,
												   'modelPatient_Contact'=>$modelPatient_Contact,
			], false, true);
		}
	}
	
	private function ajaxValidatePatients($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			if(Yii::app()->request->getPost('formSearchPatients'))
			{
				$modelPatient->setAttributes(Yii::app()->request->getPost('Patients'), false); //для unique валидатора, инициализация переменных
				
				$validatePatient=CJSON::decode(CActiveForm::validate($modelPatient, null, false));
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
		{ //меняем сценарии если нажата кнопка "сохранить" для ajax валидации
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
		//TODO generator номера МЕДКАРТ
		$modelPatient=new Patients('paid.cash.create');
//		$modelPaid_Medcard=new Paid_Medcards('paid.cash.create');
		$modelPatient_Contacts=new Patient_Contacts('paid.cash.create');
		$modelPatient_Documents=new Patient_Documents('paid.cash.create');
		
		if(Yii::app()->request->getPost('Patients') && Yii::app()->request->getPost('Paid_Medcards')
		&& Yii::app()->request->getPost('Patient_Contacts') && Yii::app()->request->getPost('Patient_Documents'))
		{
			$modelPatient->attributes=Yii::app()->request->getPost('Patients');
//			$modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
			$modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
			$modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
			$modelPatient->create_timestamp=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
//			$modelPaid_Medcard->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());

			$transaction=Yii::app()->db->beginTransaction();
			try
			{
				/**
				 * В случае успеха отдаем результат на клиента.
				 * 1 - значит успешное сохранение.
				 * 0 - возникли ошибки при сохранении данных в БД (не прошли валидацию и прочее).
				 */
				
				$recordPatient=Patients::model()->find('last_name=:last_name AND first_name=:first_name AND middle_name=:middle_name AND birthday=:birthday',
				[':last_name'=>$modelPatient->last_name, ':first_name'=>$modelPatient->first_name, ':middle_name'=>$modelPatient->middle_name, ':birthday'=>$modelPatient->birthday]);
				
				if($recordPatient===null)
				{ //проверяем, создан ли уже такой пациент, если да, то не создаем его
					if(!$modelPatient->save())
					{ //не сохранился пациент, откатываем транзакцию и выводим ошибку на клиента (0)
						$transaction->rollback();
						echo 0;
						Yii::app()->end();
					}
					$modelPatient->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
				}
				else
				{
					$modelPatient->patient_id=$recordPatient->patient_id;
				}
				
//				$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', ['patient_id'=>$modelPatient->patient_id]);
//				
//				if($recordPaid_Medcard===null)
//				{ //у пользователя нет ЭМК платных услуг
//					$modelPaid_Medcard->patient_id=$modelPatient->patient_id;
//
//					if(!$modelPaid_Medcard->save())
//					{ //запросы с ошибками, откатываем транзакцию и выводим ошибку на клиента (0)
//						$transaction->rollback();
//						echo 0;
//						Yii::app()->end();
//					}
//				}
				
				$modelPatient_Contacts->patient_id=$modelPatient->patient_id;
				$modelPatient_Documents->patient_id=$modelPatient->patient_id;
				
				if($recordPatient===null)
				{ //пациент новый, надо создать ему контакты
					if(!($modelPatient_Contacts->save() && $modelPatient_Documents->save()))
					{
						$transaction->rollback();
						echo 0;
						Yii::app()->end();
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
						$modelPatient_Contacts->patient_id=$modelPatient->patient_id;

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
						$modelPatient_Documents->patient_id=$modelPatient->patient_id;

						if(!$modelPatient_Documents->save())
						{
							$transaction->rollback();
							echo 0;
							Yii::app()->end();
						}
					}
				}
				$transaction->commit();
				echo $modelPatient->patient_id; //выводим patient_id и перенаправляем аяксом на acitonPatient()
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
	{
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