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
	public $emptyTextGrid;
	public $globalSearch=false;
	
	public $modelPaid_Service_Groups; // for search in CGridView
	
	const PAGE_SIZE=5;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function relations()
	{
		return [
			'group'=>[self::BELONGS_TO, 'Paid_Service_Groups', 'paid_service_group_id'],
			'paid_order_details'=>[self::HAS_MANY, 'Paid_Order_Details', 'paid_service_id'],
		];
	}
	
	public function rules()
	{
		return [
			['hash', 'safe'],
			['code', 'unique', 'on'=>'paid.cash.create'],
			['code', 'unique', 'on'=>'paid.cash.update'],
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.create'],
			['name, price, since_date, exp_date, code', 'required', 'on'=>'paid.cash.create'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.update'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.update'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.update'],
			['name, price, since_date, exp_date, code', 'required', 'on'=>'paid.cash.update'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.update'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.update'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.search'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.search'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.search'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.search'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.select'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cash.select'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cash.select'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.select'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.select'],
			
			['paid_service_group_id', 'type', 'type'=>'integer', 'on'=>'paid.cashAct.select'],
			['name, code, reason', 'type', 'type'=>'string', 'on'=>'paid.cashAct.select'],
			['price', 'type', 'type'=>'float', 'on'=>'paid.cashAct.select'],
			['since_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cashAct.select'],
			['exp_date', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cashAct.select'],
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
			'name'=>'Название услуги',
			'code'=>'Код услуги',
			'price'=>'Цена',
			'since_date'=>'Действует с',
			'exp_date'=>'Действует до',
			'reason'=>'Основание',
		];
	}	

	/**
	 * Метод, проверяющий есть ли в запросе хоть одно заполненное поле
	 * @return boolean
	 */
	public static function isEmpty($object)
	{
		$isEmpty=true;
		foreach($object as $value)
		{ //итератор по данному объекту
			if($value!==null && $value!=='')
			{
				$isEmpty=false;
				break;
			}
		}
		return $isEmpty;
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->with=['group'];
		$criteria->together=true;
		
		if(!self::isEmpty($this) || $this->scenario=='paid.cash.select')
		{ //поиск из раздела "Услуги"
			$criteria->compare('cast(t.paid_service_group_id as varchar)', $this->paid_service_group_id);
			$criteria->compare('lower(t.name)', mb_strtolower($this->name, 'UTF-8'), true);
			$criteria->compare('lower(t.code)', mb_strtolower($this->code, 'UTF-8'));
			
			$this->emptyTextGrid='Услуги не найдены.';
		}
		elseif($this->scenario=='paid.cashAct.select')
		{ //Отображать услуги по дате в выборе услуг для пациента + фильтр CGridView
			$dateNow=Yii::app()->dateFormatter->format('yyyy-MM-dd', time());
			$criteria->addCondition(':dateNow<=t.exp_date AND :dateNow>=t.since_date');
			$criteria->params=[':dateNow'=>$dateNow];
			
			$criteria->compare('lower(t.name)', mb_strtolower($this->name, 'UTF-8'), true);
			$criteria->compare('lower(t.code)', mb_strtolower($this->code, 'UTF-8'));		
			$criteria->compare('lower("group"."name")', mb_strtolower($this->modelPaid_Service_Groups->name, 'UTF-8'), true);
			
			$this->emptyTextGrid='Услуги не найдены.';
		}
		else
		{
			$criteria->addCondition('t.paid_service_group_id=-1');
			$this->emptyTextGrid='Необходимо заполнить поисковую форму.';
		}
		
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