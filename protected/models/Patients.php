<?php
/**
 * AR Patients
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Patients extends ActiveRecord
{
	public $patient_id;
	public $first_name;
	public $middle_name;
	public $last_name;
	public $gender;
	public $birthday;
	public $document_type;
	public $document_serie;
	public $document_number;
	public $document_who_gived;
	public $document_date_gived;
	public $address_reg;
	public $address;
	public $snils;
	public $invalid_group;
	public $phone_number;
	public $profession;
	public $job_address;
	public $create_timestamp;
	
	const PAGE_SIZE = 5;
	
	const DOCUMENT_TYPE_PASSPORT_ID = 1;
	const DOCUMENT_TYPE_PASSPORT_NAME = 'Паспорт';
	
	const DOCUMENT_TYPE_BIRTH_CERTIFICATE_ID = 2;
	const DOCUMENT_TYPE_BIRTH_CERTIFICATE_NAME = 'Свидетельство о рождении';
	
	const DOCUMENT_TYPE_RESIDENCE_PERMIT_ID = 3;
	const DOCUMENT_TYPE_RESIDENCE_PERMIT_NAME = 'Вид на жительство';
	
	const DOCUMENT_TYPE_PASSPORT_FOREIGNER_ID = 4;
	const DOCUMENT_TYPE_PASSPORT_FOREIGNER_NAME = 'Паспорт иностранного гражданина';
	
	const DOCUMENT_TYPE_IDENTITY_CARD_ID = 5;
	const DOCUMENT_TYPE_IDENTITY_CARD_NAME = 'Удостоверение личности';
	
	const DOCUMENT_TYPE_OTHER_DOCUMENT_ID = 6;
	const DOCUMENT_TYPE_OTHER_DOCUMENT_NAME = 'Другой документ';
	
	const GENDER_MALE_ID = 1;
	const GENDER_MALE_NAME = 'Мужской';
	
	const GENDER_FEMALE_ID = 2;
	const GENDER_FEMALE_NAME = 'Женский';
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return [
			//Добавление пациента
			['first_name, middle_name, last_name, gender, birthday', 'required', 'on'=>'paid.cash.create'],
			['birthday', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			['document_type, document_serie, document_number, document_who_gived, document_date_gived, address_reg, address, snils, invalid_group, phone_number, profession, job_address', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			
			//Поиск пациентов
			['first_name, middle_name, last_name, gender', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			/**********************************/
		];
	}
	
	/**
	 * Используется в форме paid/cash/search
	 */
	public static function getDocumentTypeListData()
	{
		return CHtml::listData([
					[
						'value'=>self::DOCUMENT_TYPE_PASSPORT_ID,
						'name'=>self::DOCUMENT_TYPE_PASSPORT_NAME,
					],
					[
						'value'=>self::DOCUMENT_TYPE_BIRTH_CERTIFICATE_ID,
						'name'=>self::DOCUMENT_TYPE_BIRTH_CERTIFICATE_NAME,
					],
					[
						'value'=>self::DOCUMENT_TYPE_RESIDENCE_PERMIT_ID,
						'name'=>self::DOCUMENT_TYPE_RESIDENCE_PERMIT_NAME,
					],
					[
						'value'=>self::DOCUMENT_TYPE_PASSPORT_FOREIGNER_ID,
						'name'=>self::DOCUMENT_TYPE_PASSPORT_FOREIGNER_NAME,
					],
					[
						'value'=>self::DOCUMENT_TYPE_IDENTITY_CARD_ID,
						'name'=>self::DOCUMENT_TYPE_IDENTITY_CARD_NAME,
					],
					[
						'value'=>self::DOCUMENT_TYPE_OTHER_DOCUMENT_ID,
						'name'=>self::DOCUMENT_TYPE_OTHER_DOCUMENT_NAME,
					]
		], 'value', 'name');
	}
	
	/**
	 * Используется в форме paid/cash/search
	 */
	public static function getGenderListData()
	{
		return CHtml::listData([
					[
						'value'=>self::GENDER_MALE_ID,
						'name'=>self::GENDER_MALE_NAME,
					],
					[
						'value'=>self::GENDER_FEMALE_ID,
						'name'=>self::GENDER_FEMALE_NAME,
					]
				], 'value', 'name');
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;	
		$criteria->compare('last_name', $this->last_name, true);
		$criteria->compare('first_name', $this->first_name, true);
		$criteria->compare('middle_name', $this->middle_name, true);
		$criteria->compare('gender', $this->gender, true);
		return new CActiveDataProvider('Patients',[
			'criteria'=>$criteria,
			'sort'=>[
				'defaultOrder'=>[
					'patient_id'=>CSort::SORT_DESC,
				],
			],
			'pagination'=>[
				'pageSize'=>self::PAGE_SIZE,
			],
		]);
	}
	
	public function tableName()
	{
		return 'mis.patients';
	}
}