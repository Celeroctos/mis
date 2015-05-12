<?php
/**
 * Контакты пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Patient_Contacts extends ActiveRecord
{
	public $patient_contact_id;
	public $patient_id; //FK
	public $type; //пока один тип. в дальнейшем может удалим
	public $value;
//	public $valueArrMass; //js input
	
	public $errorSummary;
	
	const MOBILE_PHONE_ID = 1;
	const MOBILE_PHONE_NAME = 'Мобильный телефон';
	
	const HOME_PHONE_ID = 2;
	const HOME_PHONE_NAME = 'Домашний телефон';
	
	const WORK_PHONE_ID = 3;
	const WORK_PHONE_NAME = 'Рабочий телефон';
	
	public function relations()
	{
		return [
			'patient_id'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}
	
	public function rules()
	{
		return [
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			
			['value', 'required', 'on'=>'paid.cash.create'],
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'mis.patient_contacts';
	}
	
	public function attributeLabels()
	{
		return [
		];
	}
	
	public function search()
	{
		
	}
}