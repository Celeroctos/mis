<?php
/**
 * AR-модель для работы с услугами платного модуля.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Services extends ActiveRecord
{
	public $paid_service_id;
	public $paid_service_group_id;
	public $name;
	public $code;
	public $price;
	public $since_date;
	public $exp_date;
	public $reason; //основание
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return [
			
		];
	}
	
	public function tableName()
	{
		return 'paid.paid_services';
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->condition='paid_service_group_id=:paid_service_group_id';
		$criteria->params=[':paid_service_group_id'=>$this->paid_service_group_id];
		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
			'sort'=>[
				'defaultOrder'=>[
					'patient_id'=>CSort::SORT_DESC,
				],
			],
		]);
	}
}