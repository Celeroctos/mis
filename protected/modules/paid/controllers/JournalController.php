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
	 * Отключаем уже подключенные скрипты
	 */
	public static function disableScripts()
	{
		Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
//		Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.js']=false;
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
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$criteria=new CDbCriteria;
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('allExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('allExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}
	}
	
	/**
	 * No paid expenses
	 */
	public function actionNotPaidExpenses()
	{
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$criteria=new CDbCriteria;
		$criteria->condition='status=:status';
		$criteria->params=[':status'=>Paid_Expenses::NOT_PAID];
		
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('notPaidExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('notPaidExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}		
	}
	
	public function actionPaidExpenses()
	{
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$criteria=new CDbCriteria;
		$criteria->condition='status=:status';
		$criteria->params=[':status'=>Paid_Expenses::PAID];
		
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('PaidExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('PaidExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
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