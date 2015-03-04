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
		$modelPaid_Medcard=new Paid_Medcards('paid.cash.search');
		$documentTypeListData=Patients::getDocumentTypeListData();
		$genderListData=Patients::getGenderListData();
		
		if(isset($_GET['ajax_grid']))
		{ //обработка кнопок грида (пагинация и прочее)
			$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient]); //processoutput загрузился один раз, снизу
			Yii::app()->end();
		}
		elseif(isset($_POST['Patients']))
		{
			if(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search_patient_ajax')) //ajaxSubmitButton, в этом случае enableajaxValidation не срабатывает.
			{ //search
				Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
				$modelPatient->setScenario('paid.cash.search');
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient], false, true); //load processoutput
				Yii::app()->end();
			}
			elseif(Yii::app()->request->isAjaxRequest && Yii::app()->request->getPost('paid_cash_search-form')) //см CActiveForm. Все по доке. (enableajaxValidation)
			{ //create
				$modelPatient->setScenario('paid.cash.create');
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$modelPatient->create_timestamp=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
				if(!$modelPatient->save())
				{
					$errors=CActiveForm::validate($modelPatient);
					Yii::app()->end($errors); //output JSON
				}
				else
				{
					$arrayJson=array();
					$arrayJson['redirectUrl']=$this->createUrl('cash/index', ['patient_id'=>Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq')]);
					Yii::app()->end(CJSON::encode($arrayJson));
				}
			}
		}
		
		$this->render('index', [
			'modelPatient'=>$modelPatient,
			'modelPaid_Medcard'=>$modelPaid_Medcard,
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