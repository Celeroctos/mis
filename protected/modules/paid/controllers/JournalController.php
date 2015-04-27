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
	 * include js files for journal page
	 */
	public static function enableScripts()
	{
		Yii::app()->clientScript->registerPackage('journal');
	}
	
	/**
	 * All expenses
	 */
	public function actionAllExpenses()
	{
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('allExpenses');
		}
		else {
			$this->renderPartial('allExpenses', null, false, true);
		}
	}
	
	/**
	 * No paid expenses
	 */
	public function actionNotPaidExpenses()
	{
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('notPaidExpenses');
		}
		else {
			$this->renderPartial('notPaidExpenses', null, false, true);
		}		
	}
	
	/**
	 * Main action
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->actionAllExpenses();
	}
}