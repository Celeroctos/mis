<?php
/**
 * Документы пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Patient_Documents extends ActiveRecord
{
	public $patient_document_id;
	public $serie;
	public $number;
	public $who_gived;
	public $date_gived;
	public $type;
	public $patient_id; //FK
	
	public $errorSummary;
	
//	public $typeArrMass;
//	public $serieArrMass;
//	public $numberArrMass;
	
	public function relations()
	{
		return [
			'patient_id'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}

	public function attributeLabels()
	{
		return [
			'serie'=>'Серия документа',
			'number'=>'Номер документа',
			'type'=>'Тип документа',
		];
	}
	
	public function rules()
	{
		return [
			['type, serie, number', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['errorSummary', 'validateRequiredFields', 'on'=>'paid.cash.create'],
			
			['type, serie, number', 'type', 'type'=>'string', 'on'=>'paid.cash.search'],
			['errorSummary', 'validateRequiredFields', 'on'=>'paid.cash.search'],
		];
	}
	
	/**
	 * Валидатор
	 * Если заполнен номер, то обязательно заполнение серии и тип, и наоборот
	 */
	public function validateRequiredFields($attribute)
	{
		if((isset($this->type) && strlen($this->type)>0)
		|| (isset($this->serie) && strlen($this->serie)>0)
		|| (isset($this->number) && strlen($this->number)>0))
		{ //если хоть одно заполнено,
			if((isset($this->type) && strlen($this->type)>0)
			&& (isset($this->serie) && strlen($this->serie)>0)
			&& (isset($this->number) && strlen($this->number)>0))
			{ //то должны быть заполнены все поля
				return;
			}
			else
			{
				$this->addError($attribute, 'Поля с документами не должны быть пустыми.');
				return;
			}
		}		
	}
	
	public static function updateDocuments($patient_id, $Patient_Documents)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			Patient_Documents::model()->deleteAll('patient_id=:patient_id', [':patient_id'=>$patient_id]);
			$i=0;
			foreach($Patient_Documents['type'] as $document)
			{
				$modelPatient_Documents=new Patient_Documents('paid.cash.create'); // передача по ссылке
				$modelPatient_Documents->type=$Patient_Documents['type'][$i];
				$modelPatient_Documents->serie=$Patient_Documents['serie'][$i];
				$modelPatient_Documents->number=$Patient_Documents['number'][$i];
				$modelPatient_Documents->patient_id=$patient_id;
				$modelPatient_Documents->save();
				$i++;
			}
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'mis.patient_documents';
	}
	
	public function search()
	{
		
	}
}