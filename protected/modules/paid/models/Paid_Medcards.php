<?php
/**
 * ЭМК платных услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Medcards extends ActiveRecord
{
	public $paid_medcard_id;
	public $paid_card_number;
	public $date_create; //timestamp
	public $enterprise_id;
	public $pacient_id;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'paid.paid_medcards';
	}
}