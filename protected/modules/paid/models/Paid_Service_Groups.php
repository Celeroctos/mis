<?php
/**
 * AR модель для работы с группами услуг платного модуля.
 * У каждой услуги есть своя группа, вложенность неограничена.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Service_Groups extends ActiveRecord
{
	public $paid_service_group_id;					
	public $name;
	public $p_id;
}