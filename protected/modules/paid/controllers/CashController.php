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
	 * Основной экш кассы.
	 * @param int $patient_id #ID пациента
	 */
	public function actionIndex($patient_id=null)
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
		
		if(!Yii::app()->request->isAjaxRequest && isset($patient_id))
		{//выбрали юзера не(!!) ajax запросом
			$modelPatient=Patients::model()->findByPk($patient_id);
			if($modelPatient===null)
			{
				//throw();
				Yii::app()->end();
			}
			
			$this->renderDuplicate($modelPatient, $modelPaid_Medcard, $modelPatient_Documents, $modelPatient_Contacts, $documentTypeListData, $genderListData);
			Yii::app()->end();
		}
		elseif(isset($_GET['ajax_grid']))
		{ //обработка кнопок грида ajax в модальном окне(пагинация и прочее)
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
				
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$modelPatient->modelPaid_Medcard->attributes=Yii::app()->request->getPost('Paid_Medcards');
//				$modelPatient->modelPatient_Documents->type=Yii::app()->request->getPost('Patient_Documents')['type'][0]; //тип всегда указан..
				$modelPatient->modelPatient_Documents->serie=Yii::app()->request->getPost('Patient_Documents')['serie'][0];
				$modelPatient->modelPatient_Documents->number=Yii::app()->request->getPost('Patient_Documents')['number'][0];
				$modelPatient->modelPatient_Contacts->value=Yii::app()->request->getPost('Patient_Contacts')['value'][0];
				
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
				
					if(!$modelPatient->save())
					{
						$transaction->rollback();
						echo CActiveForm::validate($modelPatient);
						Yii::app()->end();
					}//если ок, то идем дальше сохранять данные в другие модели (контакты и документы)
					//динамические поля, подгружаемые JSом
					$arrPhoneValues=Yii::app()->request->getPost('Patient_Contacts')['value'];
					$arrDocumentTypes=Yii::app()->request->getPost('Patient_Documents')['type'];
					$arrDocumentSeries=Yii::app()->request->getPost('Patient_Documents')['serie'];
					$arrDocumentsNumbers=Yii::app()->request->getPost('Patient_Documents')['number'];
					
					Patient_Documents::saveFewDocumentsFromForm($arrDocumentTypes, $arrDocumentSeries, $arrDocumentsNumbers, $modelPatient_Documents, $transaction);
					Patient_Contacts::saveFewPhonesFromForm($arrPhoneValues, $modelPatient_Contacts, $transaction);
					//если нет ошибок валидации в методах то идём дальше, иначе exit()
					
					$transaction->commit();
					$arrayJson=array();
					$arrayJson['redirectUrl']=$this->createUrl('cash/index', ['patient_id'=>Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq')]);
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