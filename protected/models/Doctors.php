<?php
/**
 * AR-модель докторов
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Doctors extends ActiveRecord
{
	public $id;
	public $first_name;
	public $middle_name;
	public $last_name;
	
	public static function getServiceGroupsListData()
	{
		$doctorList=Doctors::model()->findAll();
		return CHtml::listData($doctorList, 'id', 'last_name');
	}
	
	public function tableName()
	{
		return 'mis.doctors';
	}
	
	public function rules()
	{
		return [
			['id', 'type', 'type'=>'integer'],
		];
	}
	
	public function relations()
	{
		return [
			'groups'=>[self::HAS_MANY, 'Paid_Services_Doctors', 'doctor_id'],
		];
	}	
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}