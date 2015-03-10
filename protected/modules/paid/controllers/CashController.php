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
	
	/**
	 * Основной экш кассы.
	 * @param int $patient_id #ID пациента
	 */
	public function actionIndex($patient_id=null)
	{
		$modelPatient=new Patients; // Сценарий [module].[controller].[action]
		$modelPaid_Medcard=new Paid_Medcards;
		$modelPatient_Documents=new Patient_Documents;
		$modelPatient_Contacts=new Patient_Contacts;
		
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
			
			$this->render('index', ['modelPatient'=>$modelPatient,
									'modelPaid_Medcard'=>$modelPaid_Medcard,
									'modelPatient_Documents'=>$modelPatient_Documents,
									'modelPatient_Contacts'=>$modelPatient_Contacts,
									'documentTypeListData'=>$documentTypeListData,
									'genderListData'=>$genderListData,
			]);
			Yii::app()->end();
		}
		elseif(isset($_GET['ajax_grid']))
		{ //обработка кнопок грида ajax в модальном окне(пагинация и прочее)
			$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient]); //processoutput уже загрузился один раз, снизу
			Yii::app()->end();
		}
		elseif(isset($_POST['Patients']) && Yii::app()->request->getPost('Patient_Contacts'))
		{
			if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search_patient_ajax')) //ajaxSubmitButton, в этом случае enableajaxValidation не срабатывает.
			{ //search
				Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
				$modelPatient->setScenario('paid.cash.search');
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient], false, true); //load processoutput
				Yii::app()->end();
			}
			elseif(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search-form')) //см CActiveForm, офф доку. (enableajaxValidation)
			{ //create
				$modelPatient->setScenario('paid.cash.create');
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$modelPatient->create_timestamp=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
				
				$modelPatient_Contacts->setScenario('paid.cash.create');
				
				$transaction=Yii::app()->db->beginTransaction();
				try
				{
					if($modelPatient->save())
					{
						$arr_phone_numbers=Yii::app()->request->getPost('Patient_Contacts');
						foreach($arr_phone_numbers['value'] as $value)
						{
							$modelPatient_Contacts->value=$value;
							$modelPatient_Contacts->type=1; //пока тип один, может быть удалим в
							$modelPatient_Contacts->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
							if(!$modelPatient_Contacts->save()) //валидируем всё но пишем ошибку в интерфейс только от 1 поля
							{ //валидируем
								$transaction->rollback();
								$errors=CActiveForm::validate($modelPatient_Contacts, NULL, false);
								Yii::app()->end($errors); //output JSON
							}
							$modelPatient_Contacts->isNewRecord=true;
						}
						$transaction->commit();
						$arrayJson=array();
						$arrayJson['redirectUrl']=$this->createUrl('cash/index', ['patient_id'=>Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq')]);
						Yii::app()->end(CJSON::encode($arrayJson));
					}
					else
					{
						$transaction->rollback();
						$errors=CActiveForm::validate($modelPatient);
						Yii::app()->end($errors); //output JSON
					}
				}
				catch(Exception $e)
				{
					$transaction->rollback();
					throw $e;
				}
			}
		}
		
		$this->render('index', [
			'modelPatient'=>$modelPatient,
			'modelPaid_Medcard'=>$modelPaid_Medcard,
			'modelPatient_Documents'=>$modelPatient_Documents,
			'modelPatient_Contacts'=>$modelPatient_Contacts,
			'documentTypeListData'=>$documentTypeListData,
			'genderListData'=>$genderListData,
		]);
	}
	
	/*
	 * Создание платной ЭМК
	 */
	public function actionCreate()
	{
		$this->render('create');
	}
}