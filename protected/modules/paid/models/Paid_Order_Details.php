<?php
/**
 * AR-модель детализации заказа.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Order_Details extends ActiveRecord
{
	public $paid_order_detail_id;
	public $paid_order_id;
	public $paid_service_id;
	public $doctor_id;
	
	public function tableName()
	{
		return 'paid.paid_order_details';
	}
	
	public function rules()
	{
		return [
			
		];
	}
	
	public function relations()
	{
		return [
			'service'=>[self::BELONGS_TO, 'Paid_Services', 'paid_service_id'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}