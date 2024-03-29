<?php
/**
 * Направления
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Referrals extends ActiveRecord
{
	public $paid_referrals_id;
	public $paid_order_id;
	public $patient_id;
	public $doctor_id;
	public $date;
	public $status; //очень сомнительный параметр
	public $referral_number;
	
	public function tableName()
	{
		return 'paid.paid_referrals';
	}
	
	public function rules()
	{
		return [
			['date, status', 'safe'],
			['paid_order_id, patient_id', 'type', 'type'=>'integer', 'on'=>'paid.cashAct.create'], //номер заказа уникален
		];
	}
	
	public function relations()
	{
		return [
			'doctor'=>[self::BELONGS_TO, 'Doctors', 'doctor_id'],
			'order'=>[self::BELONGS_TO, 'Paid_Orders', 'paid_order_id'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}