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
		Yii::app()->clientScript->scriptMap['jquery.yiigridview.js']=false;
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
		$modelPatient=new Patients('paid.journal.search');
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		$modelPatient->attributes=Yii::app()->request->getPost('Patients');
		
		/**
		 * перенести в search() модели. (Повторы кода, некритично)...
		 */
		$criteria=new CDbCriteria;
		$criteria->with=['order.patient'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->compare('LOWER(patient.last_name)', mb_strtolower($modelPatient->last_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.first_name)', mb_strtolower($modelPatient->first_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.middle_name)', mb_strtolower($modelPatient->middle_name, 'UTF-8'));
		
		/**
		 * перенести в ->search() модели.
		 */
		if(isset($modelPaid_Expenses->date) && isset($modelPaid_Expenses->dateEnd) && strlen($modelPaid_Expenses->date)>0 && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $modelPaid_Expenses->date, $modelPaid_Expenses->dateEnd.' 23:59:59');
		}
	
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('allExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('allExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}
	}
	
	/**
	 * No paid expenses
	 */
	public function actionNotPaidExpenses()
	{
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$modelPatient=new Patients('paid.journal.search');
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		$modelPatient->attributes=Yii::app()->request->getPost('Patients');
		
		$criteria=new CDbCriteria;
		$criteria->condition='status=:status';
		$criteria->params=[':status'=>Paid_Expenses::NOT_PAID];
		$criteria->with=['order.patient'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->compare('LOWER(patient.last_name)', mb_strtolower($modelPatient->last_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.first_name)', mb_strtolower($modelPatient->first_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.middle_name)', mb_strtolower($modelPatient->middle_name, 'UTF-8'));
		
		/**
		 * перенести в ->search() модели.
		 */
		if(isset($modelPaid_Expenses->date) && isset($modelPaid_Expenses->dateEnd) && strlen($modelPaid_Expenses->date)>0 && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $modelPaid_Expenses->date, $modelPaid_Expenses->dateEnd.' 23:59:59');
		}
		
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('notPaidExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('notPaidExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}		
	}
	
	/**
	 * Paid expenses
	 */
	public function actionPaidExpenses()
	{
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$modelPatient=new Patients('paid.journal.search');
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		$modelPatient->attributes=Yii::app()->request->getPost('Patients');
		
		$criteria=new CDbCriteria;
		$criteria->condition='status=:status';
		$criteria->params=[':status'=>Paid_Expenses::PAID];
		$criteria->with=['order.patient'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->compare('LOWER(patient.last_name)', mb_strtolower($modelPatient->last_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.first_name)', mb_strtolower($modelPatient->first_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.middle_name)', mb_strtolower($modelPatient->middle_name, 'UTF-8'));
		
		/**
		 * перенести в ->search() модели.
		 */
		if(isset($modelPaid_Expenses->date) && isset($modelPaid_Expenses->dateEnd) && strlen($modelPaid_Expenses->date)>0 && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $modelPaid_Expenses->date, $modelPaid_Expenses->dateEnd.' 23:59:59');
		}
		
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('paidExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('paidExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}	
	}
	
	/**
	 * Return expenses
	 */
	public function actionPaidReturnExpenses()
	{
		$modelPaid_Expenses=new Paid_Expenses('paid.journal.all');
		$modelPatient=new Patients('paid.journal.search');
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		$modelPatient->attributes=Yii::app()->request->getPost('Patients');
		
		$criteria=new CDbCriteria;
		$criteria->condition='status=:status';
		$criteria->params=[':status'=>Paid_Expenses::RETURN_PAID];
		$criteria->with=['order.patient'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->compare('LOWER(patient.last_name)', mb_strtolower($modelPatient->last_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.first_name)', mb_strtolower($modelPatient->first_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.middle_name)', mb_strtolower($modelPatient->middle_name, 'UTF-8'));
		
		/**
		 * перенести в ->search() модели.
		 */
		if(isset($modelPaid_Expenses->date) && isset($modelPaid_Expenses->dateEnd) && strlen($modelPaid_Expenses->date)>0 && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $modelPaid_Expenses->date, $modelPaid_Expenses->dateEnd.' 23:59:59');
		}
		
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		if(!Yii::app()->request->isAjaxRequest) {
			self::enableScripts();
			$this->render('returnExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider]);
		}
		else {
			self::disableScripts();
			$this->renderPartial('returnExpenses', ['modelPatient'=>$modelPatient, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'dataProvider'=>$dataProvider], false, true);
		}
	}
	
	/**
	 * @param integer $expense_number номер направления
	 * Возвращает массив объектов ActiveRecord направлений по данному счёту.
	 */
	public function returnReferrals($expense_number)
	{
		$recordPaid_Expense=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		
		if($recordPaid_Expense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		$recordReferrals=Paid_Referrals::model()->findAll('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordPaid_Expense->paid_order_id]);
		
		return $recordReferrals;
	}
	
	/**
	 * Метод возвращает все #ID направлений для дальнейшей печати
	 * @param index $expense_number Номер счёта
	 * @return JSON
	 */
	public function actionReturnReferrals($expense_number)
	{
		$referrals=array();
		$i=0;
		foreach($this->returnReferrals($expense_number) as $value)
		{
			$referrals[$i]=$value->paid_referrals_id;
			$i++;
		}
		echo CJSON::encode($referrals);
	}
	
	/**
	 * Метод для выбора строки в журнале (в разных разрезах).
	 * @param $expense_number integer Номер счёта.
	 * @param $isPrint integer Откуда идёт обращение: из модали или из окна печати.
	 * Если isPrint==true, то выводить #ID заказа для последующей печати через cashAct/print_expense
	 */
	public function actionChooseRow($expense_number, $isPrint=null)
	{
		self::disableScripts();
		$recordExpense=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		$statusExpense=null;
		
		if($recordExpense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		switch($recordExpense->status)
		{
			case 0:
				$statusExpense=Paid_Expenses::NOT_PAID_NAME;
				break;
			case 1:
				$statusExpense=Paid_Expenses::PAID_NAME;
				break;
			case 2:
				$statusExpense=Paid_Expenses::RETURN_PAID_NAME;
		}
		
		if($isPrint)
		{
			echo $recordExpense->order->paid_order_id;
		}
		else
		{
			$this->renderPartial('chooseRow', ['recordExpense'=>$recordExpense, 'statusExpense'=>$statusExpense], false, true);
		}
	}
	
	function actionReturnOrder($expense_number)
	{
		$recordExpense=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		if($recordExpense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		echo $recordExpense->paid_order_id;
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