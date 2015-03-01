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
	
	public function actionIndex()
	{
		return $this->actionSearch();
	}
	
	public function actionSearch()
	{
		$modelPatient=new Patients('paid.cash.search'); // Сценарий [module].[controller].[action]
		$modelPaid_Medcard=new Paid_Medcards('paid.cash.search');
		$documentTypeListData=Patients::getDocumentTypeListData();
		
		if(isset($_GET['ajax']))
		{
			$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient]); //processoutput загрузился один раз, снизу
			Yii::app()->end();
		}
		
		if(isset($_POST['Patients']))
		{
			if(Yii::app()->request->isAjaxRequest)
			{
				
				Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js'] = false; //уже подключен.
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient], false, true); //load processoutput
				Yii::app()->end();
			}
		}
		
		$this->render('search', [
			'modelPatient'=>$modelPatient,
			'modelPaid_Medcard'=>$modelPaid_Medcard,
			'documentTypeListData'=>$documentTypeListData,
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