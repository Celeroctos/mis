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
				'controllers' => ['paid/cash'],
				'users'=>['@'],
			],
			[
				'deny', //запрет всем остальным и перенаправление.
				'deniedCallback' => [$this, 'redirectToDenied'],
				'controllers'    => ['paid/cash'],
			],
		];
	}
	
	public function actionIndex()
	{
		return $this->actionSearch();
	}
	
	public function actionSearch()
	{
		$model=new Medcards('paid.cash.search'); // Сценарий [module].[controller].[action]
		
		if(isset($_POST['Medcards']))
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