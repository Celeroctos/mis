<?php
/**
 * Родительский контроллер
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Controller extends CController
{
	const VALUES = '';
	
	public $layout='main';
	
	public $menu=array();
	
	public $breadcrumbs=array();
	
	public function actions()
	{
		return [
			'captcha'=>[
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			],
		];
	}
	
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
			{
				echo $error['message'];
			}
			else
			{
//				$this->render('error', $error);
				echo $error['message'];
			}
		}
	}
	
	/**
	 * Редирект пользователя, которому всё запрещено (deny).
	 * См. фильтр CAccessControlFilter
	 */
	public function redirectToLogin()
	{
		$this->redirect('/' . Yii::app()->user->loginUrl); //absolute url
	}
	
	/**
	 * Редирект аутентифицированного пользователя.
	 * См. фильтр CAccessControlFilter
	 */
	public function redirectToHomeUrl()
	{
		$this->redirect('/' . Yii::app()->homeUrl); //absolute url
	}
}