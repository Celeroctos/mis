<?php
/**
 * AR medcards
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Medcards extends ActiveRecord
{
	public $privelege_code;
	public $snils;
	public $address;
	public $address_reg;
	public $doctype;
	public $serie;
	public $docnumber;
	public $who_gived;
	public $contant;
	public $invalid_group;
	public $card_number;
	public $enterprise_id;
	public $policy_id; //id oms
	public $reg_date;
	public $work_place;
	public $work_address;
	public $post;
	public $profession;
	public $motion;
	public $address_str;
	public $address_reg_str;
	public $user_created;
	public $date_created;

	public function attributeLabels()
	{
		return [
			'card_number'=>'№ Медкарты',
			'serie'=>'Серия',
			'docnumber'=>'Номер',
			'address_reg'=>'Адрес регистрации',
			'address'=>'Адрес фактического проживания',
			'snils'=>'СНИЛС',
		];
	}
	
	public function rules()
	{
		return [
			['card_number', 'unique', 'on'=>'paid.cash.create'],
		];
	}
	
	/**
	 * Генератор
	 * @param integer $start Начальное число. С него начинается последовательность.
	 */
	public static function medcardNumberGenerator()
	{
		$rand=(int)mt_rand(1, 999) . time() . (int)mt_rand(1, 999);
		$rand_arr=str_split($rand); //в массив
		shuffle($rand_arr); //мешаем массив
		
		return substr($rand_str_out=implode($rand_arr), 0, 5) . '\\' . Yii::app()->dateformatter->format('yyyy', time());		
	}
	
	public function tableName()
	{
		return 'mis.medcards';
	}
}