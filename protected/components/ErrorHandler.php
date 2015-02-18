<?php
/**
 * Обработчик ошибок приложения.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class ErrorHandler extends CErrorHandler
{
	public function init()
	{
		if(isset(Yii::app()->controller->module->name)) 
		{
			$this->errorAction=Yii::app()->controller->module->name . '/' . Yii::app()->controller->id . "/error";
		}
		else 
		{
			$this->errorAction='service' . '/' . 'auth' . '/error';
		}
		parent::init();
	}	
}