<?php
/**
 * Журнал модуля платных услуг.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class JournalController extends MPaidController
{
	public function accessRules()
	{
		return [
			[
				'allow', //разрешить только авториз. юзерам.
				'controllers'=>['paid/cashAct'],
				'users'=>['@'],
			],
			[
				'deny', //запрет всем остальным и перенаправление.
				'deniedCallback'=>[$this, 'redirectToDenied'],
				'controllers'=>['paid/cashAct'],
			],
		];
	}
	
	/**
	 * All expenses
	 */
	public function actionAllExpenses()
	{
		Yii::app()->clientScript->registerScriptFile('/static_src/paid/js/journal.js');
		$this->render('allExpenses');
	}
}