<?php
/**
 * Загрузка файлов ролей и привязка пользователю
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class PhpAuthManager extends CPhpAuthManager
{
	public function init() 
	{
		if($this->authFile===null)
		{
			$this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
		}

		parent::init();

		if(!Yii::app()->user->isGuest)
		{
			 $this->assign(Yii::app()->user->getRole(), Yii::app()->user->id);
		}
	}
}