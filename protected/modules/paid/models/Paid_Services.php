<?php
/**
 * AR-модель для работы с услугами платного модуля.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Services extends ActiveRecord
{
	public $paid_service_id;
	public $paid_service_group_id;
	public $name;		
}