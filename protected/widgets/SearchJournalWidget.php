<?php
/**
 * Виджет для вывода поисковой формы в журнале.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class SearchJournalWidget extends Widget
{
	public function run()
	{
		$modelPatient=new Patients;
		$modelPaid_Expense=new Paid_Expenses();
		$this->render('searchJournalWidget', ['modelPatient'=>$modelPatient, 'modelPaid_Expense'=>$modelPaid_Expense]);
	}
}