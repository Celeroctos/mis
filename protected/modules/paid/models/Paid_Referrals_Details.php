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
	public $price; // стоимость услуги на момент формирования заказа. Может отличаться от реальной стоимости услуги.
	
	const PAGE_SIZE = 999;
	
	public function tableName()
	{
		return 'paid.paid_referrals_details';
	}
	
	public function rules()
	{
		return [
			
		];
	}

	public function attributeLabels()
	{
		return [
			'price'=>'Цена',
			'doctor'=>'Доктор',
		];
	}
	
	public function relations()
	{
		return [
			'service'=>[self::BELONGS_TO, 'Paid_Services', 'paid_service_id'],
			'referral'=>[self::BELONGS_TO, 'Paid_Referrals', 'paid_referral_id'],			
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function search($paid_referral_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.paid_referral_id', $paid_referral_id);
		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
			'sort'=>[
				'defaultOrder'=>[
					'paid_referral_detail_id'=>CSort::SORT_DESC,
				],
			],
			'pagination'=>[
				'pageSize'=>self::PAGE_SIZE,
			],
		]);			
	}
}