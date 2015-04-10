<?php
/**
 * Счета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Expenses extends ActiveRecord
{
	public $paid_expense_id;
	public $date;
	public $price;
	public $paid_order_id;
	public $status;
	
	const NOT_PAID = 0;
	const PAID = 1;
	public function tableName()
	{
		return 'paid.paid_expenses';
	}
	
	public function rules()
	{
		return [
			
		];
	}
	
	public function relations()
	{
		return [
			
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	
}
