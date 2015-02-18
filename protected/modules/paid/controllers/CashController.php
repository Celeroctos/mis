<?php
/**
 * Контроллер для работы кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class CashController extends MPaidController
{
	public $layout='index';
	
	public function actionIndex()
	{
		return $this->actionSearch();
	}
	
	public function actionSearch()
	{
		$model=new Medcard('paid.cash.search'); // Сценарий [module].[controller].[action]
		
		if(isset($_POST['Medcard']))
		{
			$model->attributes=Yii::app()->getPost('Medcards');
		}
		
		$this->render('index', [
			'model'=>$model,
		]);
	}
	
	/*
	 * Создание абстрактной ЭМК и карты платных услуг.
	 */
	public function actionCreate()
	{
		$this->render('create');
	}
}