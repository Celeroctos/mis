<?php
/**
 * модель AR
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Services_Doctors extends ActiveRecord
{
	public $paid_service_doctor_id;
	public $paid_service_group_id;
	public $doctor_id; //FK table doctors
	
	public function tableName()
	{
		return 'paid.paid_services_doctors';
	}
	
	public function rules()
	{
		return [
			['paid_service_group_id, doctor_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
			['paid_service_group_id, doctor_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.update'],
		];
	}

	public function relations()
	{
		return [
			'doctor'=>[self::BELONGS_TO, 'Doctors', 'doctor_id'],
			'group'=>[self::BELONGS_TO, 'Paid_Service_Groups', 'paid_service_group_id'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	
}