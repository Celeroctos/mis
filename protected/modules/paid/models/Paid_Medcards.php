<?php
/**
 * ЭМК платных услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Medcards extends ActiveRecord
{
	public $paid_medcard_id;
	public $paid_medcard_number;
	public $date_create; //timestamp
	public $enterprise_id; //FK
	public $patient_id; //FK
	public $max_number; //aggregate, no in table
	
	const START_MEDCARD_NUMBER = 1;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return [
			['paid_medcard_number', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['paid_medcard_number', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['paid_medcard_number', 'unique', 'on'=>'paid.cash.create'],
		];
	}
	
	/**
	 * Генератор
	 * @param integer $start Начальное число. С него начинается последовательность.
	 */
	public static function medcardNumberGenerator($start=Paid_Medcards::START_MEDCARD_NUMBER)
	{
		$sql='SELECT "t"."paid_medcard_number" FROM "paid"."paid_medcards" as "t"';
		$command=Yii::app()->db->createCommand($sql);
		$dataReader=$command->query();
		
		$numberCards=array();
		$i=0;
		
		foreach($dataReader as $row)
		{
			$numberCards[$i]=substr($row['paid_medcard_number'], 0, -5);
			$i++;
		}
		
		if(empty($numberCards))
		{ //fix bug
			return Paid_Medcards::START_MEDCARD_NUMBER;
		}
		else
		{
			$maxCardNumber=max($numberCards);
			return ++$maxCardNumber . '\\' . Yii::app()->dateformatter->format('yyyy', time());
		}

//		$rand=(int)mt_rand(1, 999) . time() . (int)mt_rand(1, 999);
//		$rand_arr=str_split($rand); //в массив
//		shuffle($rand_arr); //мешаем массив
//		substr($rand_str_out=implode($rand_arr), 0, 5) . '\\' . Yii::app()->dateformatter->format('yyyy', time());
	}
	
	public function attributeLabels()
	{
		return [
			'paid_medcard_number'=>'Номер карты',
		];
	}
	
	public function tableName()
	{
		return 'paid.paid_medcards';
	}
}