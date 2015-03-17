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

	public function actionServicesList()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			Paid_Service_Groups::recursServicesOut(Paid_Service_Groups::model()->findAll('p_id=:p_id', ['p_id'=>0]), 0);
			Yii::app()->end();
		}
		$this->render('servicesList');
	}
	
	/**
	 * Выбрали пациента.
	 * @param int $patient_id #ID пациента
	 */
	public function actionPatient($patient_id)
	{
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
	{
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
	
	/*
	 * Создание платной ЭМК
	 */
	public function actionCreate()
	{
		$this->render('create');
	}
}