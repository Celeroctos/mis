<?php
/**
 * ЭМК платных услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Medcards extends ActiveRecord
{
	public $paid_medcard_id;
	public $paid_medcard_number;
	public $date_create; //timestamp
	public $enterprise_id; //FK
	public $patient_id; //FK
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return [
			['paid_medcard_number', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['paid_medcard_number', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
		];
	}
	
	public function tableName()
	{
		return 'paid.paid_medcards';
	}
}