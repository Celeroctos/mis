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
	
	public $doctorName; //user in CGridView (chooseExpenseServices)
	public $hash; //use in CGridView id
	
	const PAGE_SIZE = 7; //use in pagination Yii
	
	public function tableName()
	{
		return 'paid.paid_order_details';
	}
	
	public function rules()
	{
		return [
			['hash', 'type', 'type'=>'string']
		];
	}
	
	public function relations()
	{
		return [
			'service'=>[self::BELONGS_TO, 'Paid_Services', 'paid_service_id'],
			'doctor'=>[self::BELONGS_TO, 'Doctors', 'doctor_id'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}