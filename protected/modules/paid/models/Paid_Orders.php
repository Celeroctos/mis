<?php
/**
 * AR-модель заказов услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Orders extends ActiveRecord
{
	public $paid_order_id;
	public $name;
	public $order_number;
	public $patient_id; //fk table mis.patients
	public $user_create_id;
//	public $paid_expense_id;
	
	/**
	 * Генератор номера заказа
	 * @return type
	 */
	public static function generateRandNumber()
	{
		$rand=time() . (int)mt_rand(1, 999) . mt_rand(1, 999);
		$rand_arr=str_split($rand); //в массив
		shuffle($rand_arr); //мешаем массив
		
		return substr($rand_str_out=implode($rand_arr), 0, 9);
	}
	
	public function tableName()
	{
		return 'paid.paid_orders';
	}
	
	public function rules()
	{
		return [
			['order_number', 'unique'], //номер заказа уникален
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