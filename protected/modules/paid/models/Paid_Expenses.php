<?php
/**
 * Счета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Expenses extends ActiveRecord
{
	public $paid_expense_id;
	public $date;
	public $price;
	public $paid_order_id; //ONE TO ONE
	public $status;
	
	const NOT_PAID = 0;
	const PAID = 1;
	
	public function tableName()
	{
		return 'paid.paid_expenses';
	}
	
	public function rules()
	{
		return [
			['paid_order_id', 'unique'],
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
	
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('status='. self::NOT_PAID);
		
		return new CActiveDataProvider('Paid_Expenses', [
			'criteria'=>$criteria,
//			'sort'=>[
//				'defaultOrder'=>[
//					'patient_id'=>CSort::SORT_DESC,
//				],
//			],
//			'pagination'=>[
//				'pageSize'=>self::PAGE_SIZE,
//			],
		]);		
	}
}
