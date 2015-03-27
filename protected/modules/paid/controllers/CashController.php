<?php
/**
 * Контроллер для работы кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class CashController extends MPaidController
{
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
	
	private function renderDuplicate($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts, $documentTypeListData, $genderListData)
	{
		$this->render('index', ['modelPatient'=>$modelPatient,
								'modelPaid_Medcard'=>$modelPaid_Medcard,
								'modelPatient_Documents'=>$modelPatient_Documents,
								'modelPatient_Contacts'=>$modelPatient_Contacts,
								'documentTypeListData'=>$documentTypeListData,
								'genderListData'=>$genderListData,
		]);
	}
	
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
		$modelPaid_Service->globalSearch=true; //жесткое или нежёсткое сравнение по группам (для г
		
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
		
		$this->ajaxValidatePaidServiceGroup($modelPaid_Service_Group); //сначала валидируем.
		
		if(Yii::app()->request->getPost('Paid_Service_Groups'))//после ajax валидации CActiveForm отправляет submit на форму
		{
			$modelPaid_Service_Group->attributes=Yii::app()->request->getPost('Paid_Service_Groups');
			
			if($modelPaid_Service_Group->save()) 
			{
				$this->redirect(['cash/groups', 'group_id'=>Yii::app()->db->getLastInsertID('paid.paid_service_groups_paid_service_group_id_seq')]);
			}
		}
		$this->renderPartial('addGroupForm', ['modelPaid_Service_Group'=>$modelPaid_Service_Group], false, true);
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
		$modelPaid_Service->paid_service_group_id=$group_id;
		
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
			$modelPaid_Service->save();
			$this->redirect(Yii::app()->request->urlReferrer);
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
	{ //TODO REFAC
		if(isset($patient_id))
		{//выбрали юзера не(!!) ajax запросом
//			$recordPatient_Documents=Patient_Documents::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
//			$recordPatient_Contacts=Patient_Contacts::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
//			$modelPatient_Documents=isset($recordPatient_Documents) ? $recordPatient_Documents : $modelPatient_Documents;
//			$modelPatient_Contacts=isset($recordPatient_Contacts) ? $recordPatient_Contacts : $modelPatient_Contacts;
			$modelPatient=Patients::model()->findByPk($patient_id);
			$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', [':patient_id'=>$patient_id]);
			
			if($modelPatient===null)
			{ //нет такого пациента
				throw new CHttpException(404, 'Пациент на найден!');
			}
			elseif($recordPaid_Medcard===null)
			{ //нет медкарты у пациента, нужно создать
				$modelPaid_Medcard=new Paid_Medcards('paid.medcard.create');
				$modelPaid_Medcard->paid_medcard_number=uniqid('', true); //TODO временно
				$modelPaid_Medcard->enterprise_id=null;
				$modelPaid_Medcard->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
				$modelPaid_Medcard->patient_id=$patient_id;
				$modelPaid_Medcard->save();
			}
			elseif($recordPaid_Medcard!==null)
			{ //уже есть ЭМК платных услуг
				$modelPaid_Medcard=$recordPaid_Medcard;
			}
			$this->render('patient', ['modelPatient'=>$modelPatient, 'modelPaid_Medcard'=>$modelPaid_Medcard]);
		}		
	}
	
	/**
	 * Основной экш кассы.
	 */
	public function actionIndex()
	{ //TODO REFACTORING
		$modelPatient=new Patients;
		$modelPaid_Medcard=new Paid_Medcards;
		$modelPatient_Documents=new Patient_Documents;
		$modelPatient_Contacts=new Patient_Contacts;
		
		/**vars For search in CGridView**/
		$modelPatient->modelPaid_Medcard=$modelPaid_Medcard;
		$modelPatient->modelPatient_Contacts=$modelPatient_Contacts;
		$modelPatient->modelPatient_Documents=$modelPatient_Documents;
		
		$documentTypeListData=Patients::getDocumentTypeListData();
		$genderListData=Patients::getGenderListData();
		
		if(isset($_GET['ajax_grid']))
		{ //обработка кнопок грида ajax в модальном окне(пагинация и прочее)
			$modelPatient->setScenario('paid.cash.search');
			$modelPatient->modelPaid_Medcard->setScenario('paid.cash.search');
			$modelPatient->modelPatient_Documents->setScenario('paid.cash.search');
			$modelPatient->modelPatient_Contacts->setScenario('paid.cash.search');
			
			$modelPatient->attributes=Yii::app()->request->getPost('Patients');
			$modelPatient->modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
			$modelPatient->modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
			$modelPatient->modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
			
			$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient]); //processoutput уже загрузился один раз, снизу
			Yii::app()->end();
		}
		elseif(isset($_POST['Patients']) && Yii::app()->request->getPost('Paid_Medcards') && Yii::app()->request->getPost('Patient_Contacts') && Yii::app()->request->getPost('Patient_Documents'))
		{
			if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search_patient_ajax')) //ajaxSubmitButton, в этом случае enableajaxValidation не срабатывает.
			{ //search
				Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
				
				$modelPatient->setScenario('paid.cash.search');
				$modelPatient->modelPaid_Medcard->setScenario('paid.cash.search');
				$modelPatient->modelPatient_Documents->setScenario('paid.cash.search');
				$modelPatient->modelPatient_Contacts->setScenario('paid.cash.search');
				
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$modelPatient->modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
				$modelPatient->modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
				$modelPatient->modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
				
				$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient], false, true); //load processoutput
				Yii::app()->end();
			}
			elseif(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search-form')) //см CActiveForm, офф доку. (enableajaxValidation)
			{ //create (кнопка сохранить, submitbutton)
				$modelPatient->setScenario('paid.cash.create');
				$modelPatient_Contacts->setScenario('paid.cash.create');
				$modelPatient_Documents->setScenario('paid.cash.create');
				
				$transaction=Yii::app()->db->beginTransaction();
				try
				{
					$modelPatient->attributes=Yii::app()->request->getPost('Patients');
					$modelPatient->create_timestamp=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
					$modelPatient_Contacts->attributes=Yii::app()->request->getPost('Patient_Contacts');
					$modelPatient_Documents->attributes=Yii::app()->request->getPost('Patient_Documents');
					
					if(!$modelPatient->save())
					{
						$transaction->rollback();
						echo CActiveForm::validate($modelPatient);
						Yii::app()->end();
					}//если ок, то идем дальше сохранять данные в другие модели (контакты и документы)
					//динамические поля, подгружаемые JSом
					
					Patient_Documents::saveFewDocumentsFromForm($modelPatient_Documents, $transaction);
					Patient_Contacts::saveFewPhonesFromForm($modelPatient_Contacts, $transaction);
					//если нет ошибок валидации в методах то идём дальше, иначе exit()
					
					$transaction->commit();
					$arrayJson=array();
					$arrayJson['redirectUrl']=$this->createUrl('cash/patient', ['patient_id'=>Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq')]);
					Yii::app()->end(CJSON::encode($arrayJson));
				}
				catch(Exception $e)
				{
					$transaction->rollback();
					throw $e;
				}
			}
		}
		$this->renderDuplicate($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts, $documentTypeListData, $genderListData);
	}
	
//	/*
//	 * Создание платной ЭМК
//	 */
//	public function actionCreate()
//	{
//		$this->render('create');
//	}
}