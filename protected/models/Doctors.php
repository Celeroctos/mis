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
	
	public $hash; //используется для id CGridView
	const PAGE_SIZE = 7;
	
	public static function getServiceGroupsListData()
	{
		$criteria=new CDbCriteria;
		$criteria->order='"t"."last_name"'; // add sort
		$doctorList=Doctors::model()->findAll($criteria);
		return CHtml::listData($doctorList, 'id', 'last_name');
	}
	
	public function tableName()
	{
		return 'mis.doctors';
	}
	
	public function attributeLabels()
	{
		return [
			'last_name'=>'Врач', //
			'first_name'=>'Имя',
			'middle_name'=>'Отчество',
		];
	}
	
	public function rules()
	{
		return [
			['id', 'type', 'type'=>'integer'],
			['hash, first_name, middle_name, last_name', 'type', 'type'=>'string', 'on'=>'paid.cashAct.search'],
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