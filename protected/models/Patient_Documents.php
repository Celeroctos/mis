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
			['type, serie, number', 'type', 'type'=>'string', 'on'=>'paid.cash.search']
		];
	}
	
	public static function saveFewDocumentsFromForm($modelPatient_Documents, $transaction)
	{
		$arrDocumentTypes=isset(Yii::app()->request->getPost('Patient_Documents')['typeArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['typeArrMass'] : [];
		$arrDocumentSeries=isset(Yii::app()->request->getPost('Patient_Documents')['serieArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['serieArrMass'] :[];
		$arrDocumentsNumbers=isset(Yii::app()->request->getPost('Patient_Documents')['numberArrMass']) ? Yii::app()->request->getPost('Patient_Documents')['numberArrMass'] : [];
		
		$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');
		if($modelPatient_Documents->save())
		{ //прошёл первый сейв от обычных инпутов Yii
			$x=0;
			foreach($arrDocumentTypes as $value)
			{
				$modelPatient_Documents->type=$arrDocumentTypes[$x];
				$modelPatient_Documents->serie=$arrDocumentSeries[$x];
				$modelPatient_Documents->number=$arrDocumentsNumbers[$x];
				$modelPatient_Documents->patient_id=Yii::app()->db->getLastInsertID('mis.patients_patient_id_seq');

				if(!$modelPatient_Documents->save())
				{
					$transaction->rollback();
					echo CActiveForm::validate($modelPatient_Documents, NULL, false);
					Yii::app()->end();
				}
				unset($modelPatient_Documents); //сохранение валидации, не работает save() при повторном обращении..
				$modelPatient_Documents=new Patient_Documents('paid.cash.create');
				$x++;
			}
		}
		else
		{
			$transaction->rollback();
			echo CActiveForm::validate($modelPatient_Documents, NULL, false);
			Yii::app()->end();
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
	
	public function attributeLabels()
	{
		return [
		];
	}
	
	public function search()
	{
		
	}
}