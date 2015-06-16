<?php
/**
 * Виджет для вывода поисковой формы в возврате платежей.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class SearchReturnPaymentWidget extends Widget
{
	public $modelPaid_Expense;
	public $modelPatient;
	
	public function run()
	{
		$this->render('searchReturnPaymentWidget', ['modelPaid_Expense'=>$this->modelPaid_Expense, 'modelPatient'=>$this->modelPatient]);
	}
}