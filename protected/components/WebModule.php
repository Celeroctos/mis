<?php
/**
 * Родительский модуль
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class WebModule extends CWebModule
{
	public function init()
	{
		$loginUrl=$this->name.'/service/login';
		Yii::app()->user->loginUrl = Yii::app()->createUrl($loginUrl);
		parent::init();
	}
}