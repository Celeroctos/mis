<?php
/**
 * модель AR
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Services_Doctors extends ActiveRecord
{
	public $paid_service_doctor_id;
	public $paid_service_group_id;
	public $doctor_id;
	
	public function tableName()
	{
		return 'paid.paid_services_doctors';
	}
	
	public function rules()
	{
		return [
			['paid_service_group_id, doctor_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	
}