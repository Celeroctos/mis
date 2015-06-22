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
	public $number_contract; // порядковый номер договора на оказание услуг пациенту.
	public $max_number; //aggregate
//	public $paid_expense_id;
	
	/**
	 * Начальное число для генератора номера счёта. Задаётся при внедрении к заказчику.
	 */	
	const START_SEQUENCE = 0;
	
	/**
	 * Генератор номера заказа/счёта
	 * @return type
	 */
	public static function generateRandNumber()
	{
		$rand=time() . (int)mt_rand(1, 999) . mt_rand(1, 999);
		$rand_arr=str_split($rand); //в массив
		shuffle($rand_arr); //мешаем массив
		
		return substr($rand_str_out=implode($rand_arr), 0, 9);
	}
	
	/**
	 * Генератор
	 * @param integer $start Начальное число. С него начинается последовательность.
	 */
	public static function orderSequenceNumber($start=Paid_Orders::START_SEQUENCE)
	{
		$criteria=new CDbCriteria;
		$criteria->select='max("t"."order_number") as "max_number"';
		$recordOrder=Paid_Orders::model()->find($criteria);
		
		if($recordOrder===null)
		{
			return $start;
		}
		else
		{
			return ++$recordOrder->max_number;
		}
		
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
			'patient'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}