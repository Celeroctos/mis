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
	
	public function tableName()
	{
		return 'mis.medcards';
	}
}