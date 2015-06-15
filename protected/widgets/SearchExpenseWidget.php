<?php
/**
 * Виджет для вывода поисковой формы в журнале.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class SearchExpenseWidget extends Widget
{
	public $modelPaid_Expense;
	public $modelPatient;
	
	public function run()
	{
		$this->render('searchExpenseWidget', ['modelPaid_Expense'=>$this->modelPaid_Expense, 'modelPatient'=>$this->modelPatient]);
	}
}