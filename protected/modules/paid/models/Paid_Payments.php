<?php
/**
 * Платёж
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Payments extends ActiveRecord
{
	public $paid_payment_id;
	public $paid_expense_id;
	public $date_delete;
	public $reason_date_delete;
	public $user_delete_id;

	public function tableName()
	{
		return 'paid.paid_payments';
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