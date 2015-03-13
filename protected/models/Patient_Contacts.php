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
	
	const MOBILE_PHONE_ID = 1;
	const MOBILE_PHONE_NAME = 'Мобильный телефон';
	
	const HOME_PHONE_ID = 2;
	const HOME_PHONE_NAME = 'Домашний телефон';
	
	const WORK_PHONE_ID = 3;
	const WORK_PHONE_NAME = 'Рабочий телефон';
	
	public function relations()
	{
		return [
			'patient_id'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}
	
	public function rules()
	{
		return [
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['value', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
		];
	}
	
	/**
	 * Метод для сохранения нескольких значений телефонов (с формы html) у одного пациента.
	 * @param array $arrPhoneValues
	 * @param Patient_Contacts $modelPatient_Contacts
	 * @param CDbTransaction $transaction
	 */
	public static function saveFewPhonesFromForm($modelPatient_Contacts, $transaction)
	{
		$arrPhoneValues=isset(Yii::app()->request->getPost('Patient_Contacts')['valueArrMass']) ? Yii::app()->request->getPost('Patient_Contacts')['valueArrMass'] : [];
		$modelPatient_Contacts->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
		$modelPatient_Contacts->type=1;
		if($modelPatient_Contacts->save())
		{
			foreach($arrPhoneValues as $value)
			{
				$modelPatient_Contacts->value=$value;
				$modelPatient_Contacts->type=1; //пока тип один, может быть удалим в
				$modelPatient_Contacts->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');

				if(!$modelPatient_Contacts->save())
				{
					$transaction->rollback(); //откат если хоть одно поле с ошибкой
					echo CActiveForm::validate($modelPatient_Contacts, NULL, false);
					Yii::app()->end();
				}
				unset($modelPatient_Contacts); //косяк с сохранением валидации, не работает save() при повторном обращении..
				$modelPatient_Contacts=new Patient_Contacts('paid.cash.create');
			}
		}
		else
		{
			$transaction->rollback();
			echo CActiveForm::validate($modelPatient_Contacts, NULL, false);
			Yii::app()->end();			
		}
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
		];
	}
	
	public function search()
	{
		
	}
}