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
	
//	public static function saveFewDocumentsFromForm($modelPatient_Documents, $transaction)
//	{
//		$arrDocumentTypes=isset(Yii::app()->request->getPost('Patient_Documents')['typeArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['typeArrMass'] : [];
//		$arrDocumentSeries=isset(Yii::app()->request->getPost('Patient_Documents')['serieArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['serieArrMass'] :[];
//		$arrDocumentsNumbers=isset(Yii::app()->request->getPost('Patient_Documents')['numberArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['numberArrMass'] : [];
//		
//		$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
//		if($modelPatient_Documents->save())
//		{ //прошёл первый сейв от обычных инпутов Yii
//			
//			unset($modelPatient_Documents); //сохранение валидации, не работает save() при повторном обращении..
//			$modelPatient_Documents=new Patient_Documents('paid.cash.create');
//			
//			foreach($arrDocumentTypes as $key=>$value)
//			{
//				$modelPatient_Documents->type=$arrDocumentTypes[$key];
//				$modelPatient_Documents->serie=$arrDocumentSeries[$key];
//				$modelPatient_Documents->number=$arrDocumentsNumbers[$key];
//				$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
//
//				if(!$modelPatient_Documents->save())
//				{
//					$transaction->rollback();
//					echo CActiveForm::validate($modelPatient_Documents, NULL, false);
//					Yii::app()->end();
//				}
//				unset($modelPatient_Documents); //сохранение валидации, не работает save() при повторном обращении..
//				$modelPatient_Documents=new Patient_Documents('paid.cash.create');
//			}
//		}
//		else
//		{
//			$transaction->rollback();
//			echo CActiveForm::validate($modelPatient_Documents, NULL, false);
//			Yii::app()->end();
//		}
//	}
	
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