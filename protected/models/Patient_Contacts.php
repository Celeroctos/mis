<?php
/**
 * Контакты пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Patient_Contacts extends ActiveRecord
{
	public $patient_contact_id;
	public $patient_id; //FK
	public $type; //пока один тип. в дальнейшем может удалим
	public $value;
//	public $valueArrMass; //js input
	
	public $errorSummary;
	
	const MOBILE_PHONE_ID = 1;
	const MOBILE_PHONE_NAME = 'Мобильный телефон';
	
	const HOME_PHONE_ID = 2;
	const HOME_PHONE_NAME = 'Домашний телефон';
	
	const WORK_PHONE_ID = 3;
	const WORK_PHONE_NAME = 'Рабочий телефон';
	
	const TYPE = 1; //сомнительный параметр
	
	public function relations()
	{
		return [
			'patient_id'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}
	
	/**
	 * Обновление контактов у пациента
	 * @param integer $patient_id
	 * @param mixed $Patient_Contacts $_POST данные
	 * @throws Exception
	 */
	public static function updateContacts($patient_id, $Patient_Contacts)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			Patient_Contacts::model()->deleteAll('patient_id=:patient_id', [':patient_id'=>$patient_id]);
			foreach($Patient_Contacts as $key=>$contact)
			{
				if($key==='value')
				{
					continue;
				}
				$modelPatient_Contacts=new Patient_Contacts('paid.cash.create'); // передача по ссылке
				$modelPatient_Contacts->value=$contact;
				$modelPatient_Contacts->type=1; //сомнительный параметр
				$modelPatient_Contacts->patient_id=$patient_id;
				$modelPatient_Contacts->save();
			}
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	public function rules()
	{
		return [
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			
			['value', 'required', 'on'=>'paid.cash.create'],
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'mis.patient_contacts';
	}
	
	public function attributeLabels()
	{
		return [
			'value'=>'Телефон',
		];
	}
	
	public function search()
	{
		
	}
}