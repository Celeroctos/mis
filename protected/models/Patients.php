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
	
	public function tableName()
	{
		return 'mis.patients';
	}
}