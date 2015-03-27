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
	
	public $hash;
	public $globalSearch=false;
	const PAGE_SIZE=7;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function relations()
	{
		return [
			'group'=>[self::BELONGS_TO, 'Paid_Service_Groups', 'paid_service_group_id'],
		];
	}
	
	public function rules()
	{
		return [
			['hash', 'safe'],
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.create'],
			['name, price, since_date, exp_date', 'required', 'on'=>'paid.cash.create'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.update'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.update'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.update'],
			['name, price, since_date, exp_date', 'required', 'on'=>'paid.cash.update'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.update'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.update'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.search'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.search'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.search'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.search'],
		];
	}
	
	public function tableName()
	{
		return 'paid.paid_services';
	}
	
	public function attributeLabels()
	{
		return [
			'paid_service_id'=>'ID',
			'paid_service_group_id'=>'Группа',
			'name'=>'Название',
			'code'=>'Код услуги',
			'price'=>'Цена',
			'since_date'=>'Действует с',
			'exp_date'=>'Действует до',
			'reason'=>'Основание',
		];
	}	
	
	public function search()
	{
		$criteria=new CDbCriteria;

		if(!$this->globalSearch) //см CashController::acionSearchServicesResult()
		{ //жесткое сравнение (используется при выводе по группам, если не указана группа - ничего не выводить)
			$criteria->condition='cast(paid_service_group_id as varchar)=:paid_service_group_id';
			$criteria->params=[':paid_service_group_id'=>$this->paid_service_group_id];
		}
		$criteria->compare('cast(paid_service_group_id as varchar)', $this->paid_service_group_id);
		$criteria->compare('name', $this->name);
		$criteria->compare('code', $this->code);
		
		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
			'sort'=>[
				'defaultOrder'=>[
					'paid_service_id'=>CSort::SORT_DESC,
				],
			],
			'pagination'=>[
				'pageSize'=>self::PAGE_SIZE,
			],
		]);
	}
}