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
	public $address_reg;
	public $address;
	public $snils;
	public $invalid_group;
	public $phone_number;
	public $profession;
	public $work_address;
	public $work_place;
	public $address_str;
	public $address_reg_str;
	public $create_timestamp;
	
	/**search vars for CGridView**/
	public $hash; //id CGridView. Для изменения ID
	public $modelPatient_Documents; // table patient_documents (activeRecord object)
	public $modelPatient_Contacts; // table patient_contacts (activeRecord object)
	public $modelPaid_Medcard; // table paid_medcards (activeRecord object)
	
	public $errorSummary;
	public $emptyText; // for CGridView emptyGrid
	
	const PAGE_SIZE = 10;
	
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
	
	public function relations()
	{
		return [
			'documents'=>[self::HAS_MANY, 'Patient_Documents', 'patient_id'],
			'contacts'=>[self::HAS_MANY, 'Patient_Contacts', 'patient_id'],
			'paid_medcards'=>[self::HAS_MANY, 'Paid_Medcards', 'patient_id'],
		];
	}
	
	public function rules()
	{
		return [
			['hash', 'type', 'type'=>'string'],
			//Добавление пациента
			['first_name, middle_name, last_name, birthday, gender', 'required', 'on'=>'paid.cash.create'],
			['birthday', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.create'],
			['address_reg, address, gender, snils, invalid_group, profession, work_address', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['first_name', 'unique', 'criteria'=>['condition'=>'middle_name=:middle_name AND last_name=:last_name AND birthday=:birthday', 
												  'params'=>[':middle_name'=>$this->middle_name, ':last_name'=>$this->last_name, ':birthday'=>$this->birthday]], 
			'message'=>'Такой пациент уже существует в базе данных.',
			'on'=>'paid.cash.create'],
			
			//Редактирование пациента
			['first_name, middle_name, last_name, birthday, gender', 'required', 'on'=>'paid.cash.update'],
			['birthday', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.update'],
			['address_reg, address, gender, snils, invalid_group, profession, work_address', 'type', 'type'=>'string', 'on'=>'paid.cash.update'],
			
			//Поиск пациентов
			['first_name, middle_name, last_name, gender', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['birthday', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cash.search'],
			['address_reg, address, snils, invalid_group, profession, work_address', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['errorSummary', 'validateRequiredLastName', 'on'=>'paid.cash.search'],
		];
	}
	
	/**
	 * Валидатор
	 * Если указан хоть один из (ФИО, дата рождения), то нужно заполнить их все
	 */
	public function validateRequiredLastName($attribute)
	{
		if((isset($this->first_name) && strlen($this->first_name)>0)
		|| (isset($this->middle_name) && strlen($this->middle_name)>0)
		|| (isset($this->last_name)  && strlen($this->last_name)>0)
		|| (isset($this->birthday) && strlen($this->birthday)>0))
		{ //если хоть одно заполнено,
			if((isset($this->first_name) && strlen($this->first_name)>0)
			&& (isset($this->middle_name) && strlen($this->middle_name)>0)
			&& (isset($this->last_name)  && strlen($this->last_name)>0)
			&& (isset($this->birthday)  && strlen($this->birthday)>0))
			{ //то должны быть заполнены все поля
				return;
			}
			else
			{
				$this->addError($attribute, 'Необходимо заполнить поля ФИО и дату рождения.');
				return;
			}
		}
	}
	
	/**
	 * Валидатор
	 * Если заполнено поле birthday, то необходимо заполнить ещё поля.
	 * @param type $attribute
	 */
//	public function validateRequiredBirthday($attribute)
//	{
//		$error=false;
//		
//		if(!empty($this->birthday))
//		{
//			$error=true;
//			
//			foreach($this as $key=>$value)
//			{ //итератор по объекту.
//				if($key!='birthday' && !empty($value))
//				{
//					$error=false;
//				}
//			}
//		}
//		if($error===true)
//		{
//			$this->addError($attribute, 'Заполните ещё поля для запроса!');
//			return;
//		}
//	}
	
	/**
	 * Используется в форме paid/cash/search
	 */
	public static function getDocumentTypeListData()
	{
		return CHtml::listData([
					[
						'value'=>null,
						'name'=>'',
					],
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
						'value'=>null,
						'name'=>'',
					],
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
	
	/**
	 * Метод, проверяющий есть ли в запросе хоть одно заполненное поле
	 * @param CDbCriteria $criteria
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
		if(!self::isEmpty($this)
		|| !self::isEmpty($this->modelPatient_Documents)
		|| !self::isEmpty($this->modelPatient_Contacts)
		|| !self::isEmpty($this->modelPaid_Medcard))
		{
			$criteria->with=['contacts'=>['select'=>''], 'documents'=>['select'=>''], 'paid_medcards'=>['select'=>'']]; //не выводим в таблице grid, FIX for POSTGRESQL
			//joinType - LEFT JOIN (default value)
			$criteria->together=true;
			$criteria->select=['t.first_name', 't.last_name', 't.middle_name', 't.birthday'];
			$criteria->compare('lower(t.last_name)', mb_strtolower($this->last_name, 'UTF-8'));
			$criteria->compare('lower(t.first_name)', mb_strtolower($this->first_name, 'UTF-8'));
			$criteria->compare('lower(t.middle_name)', mb_strtolower($this->middle_name, 'UTF-8'));
			$criteria->compare('t.birthday', $this->birthday);
			$criteria->compare('lower(t.gender)', mb_strtolower($this->gender, 'UTF-8'));
			$criteria->compare('paid_medcards.paid_medcard_number', $this->modelPaid_Medcard->paid_medcard_number);
			$criteria->compare('documents.type', $this->modelPatient_Documents->type);
			$criteria->compare('lower(documents.serie)', mb_strtolower($this->modelPatient_Documents->serie, 'UTF-8'));
			$criteria->compare('lower(documents.number)', mb_strtolower($this->modelPatient_Documents->number, 'UTF-8'));
			$criteria->compare('lower(contacts.value)', mb_strtolower($this->modelPatient_Contacts->value, 'UTF-8'));
			$criteria->group='t.patient_id';
			$this->emptyText = 'Пациент не найден.'; //for GridView
		}
		else
		{
			$criteria->addCondition('t.patient_id=-1');
			$this->emptyText = 'Необходимо заполнить поисковую форму.';
		}
		
		return new CActiveDataProvider('Patients', [
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