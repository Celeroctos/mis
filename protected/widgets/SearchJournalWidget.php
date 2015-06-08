<?php
/**
 * Виджет для вывода поисковой формы в журнале.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class SearchJournalWidget extends Widget
{
	public $modelPaid_Expense;
	
	public function run()
	{
		$this->render('searchJournalWidget', ['modelPaid_Expense'=>$this->modelPaid_Expense]);
	}
}