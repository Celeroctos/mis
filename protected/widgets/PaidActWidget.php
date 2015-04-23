<?php
/**
 * Экш действий кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class PaidActWidget extends CWidget
{
	public $modelPatient;
	
	public function run()
	{
		$this->render('paidActWidget', ['modelPatient'=>$this->modelPatient]);
	}
}