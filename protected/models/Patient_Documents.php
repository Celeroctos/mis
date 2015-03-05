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
	
	public function relations()
	{
		return [
			'patient_id'=>[self::BELONGS_TO, 'Patients', 'patient_id'],
		];
	}
	
	public function rules()
	{
		return [
		];
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'mis.patient_documents';
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