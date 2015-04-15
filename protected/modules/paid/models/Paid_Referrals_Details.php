<?php
/**
 * Детали направлений
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Referrals_Details extends ActiveRecord
{
	public $paid_referral_detail_id;
	public $paid_service_id;
	public $paid_referral_id;
//	public $doctor_id;
	
	public function tableName()
	{
		return 'paid.paid_referrals_details';
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