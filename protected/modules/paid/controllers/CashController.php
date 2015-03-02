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
		return $this->actionSearch(Yii::app()->request->getQuery('patient_id'));
	}
	
	/**
	 * Если указан данный параметр, то юзаем выбранного пациента
	 * @param int $patient_id #ID пациента
	 */
	public function actionSearch($patient_id=null)
	{	
		$modelPatient=new Patients('paid.cash.search'); // Сценарий [module].[controller].[action]
		$modelPaid_Medcard=new Paid_Medcards('paid.cash.search');
		$documentTypeListData=Patients::getDocumentTypeListData();
		
		if(isset($_GET['ajax_grid']))
		{
			$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient]); //processoutput загрузился один раз, снизу
			Yii::app()->end();
		}
		elseif(isset($_POST['Patients']))
		{
			if(Yii::app()->request->isAjaxRequest)
			{ //search
				Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
				$this->renderPartial('searchResultGrid', ['modelPatient'=>$modelPatient], false, true); //load processoutput
				Yii::app()->end();
			}
		elseif(isset($_POST['Patients']))
		{
			echo 423423;
			Yii::app()->end();
		}
//			elseif()
//			{ //add patient
//				$modelPatient->setScenario('paid.cash.create'); //меняем сценарий
//				$modelPaid_Medcard->setScenario('paid.cash.create'); //меняем сценарий
//				
//				$modelPatient->attributes=Yii::app()->request->getPost('Patients');
//				
//				if($modelPatient->save())
//				{
//					echo 1; //1 - успешное добавление пациента
//				}
//				else
//				{
//					echo 0; //ошибка
//				}
//				Yii::app()->end();
//			}
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