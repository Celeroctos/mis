<?php
/**
 * Родительский контроллер
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Controller extends CController
{
	const VALUES = '';
	const ERROR_LOGIN_JS = 'ERROR_LOGIN'; //ajax запросы, если нет авторизации
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
	
//	public function actionError()
//	{
//		if($error=Yii::app()->errorHandler->error)
//		{
//			if(Yii::app()->request->isAjaxRequest)
//			{
//				echo $error['message'];
//			}
//			else
//			{
//				$this->render('error', $error);
//				echo $error['message'];
//			}
//		}
//	}

	public function filters()
	{
		return ['accessControl',];
	}

	/**
	 * Редирект пользователя в случае запрета доступа к контроллеру
	 */
	public function redirectToDenied()
	{
		Yii::app()->user->logout(true);
		if(Yii::app()->request->isAjaxRequest)
		{
			echo self::ERROR_LOGIN_JS;
		}
		else
		{
			$this->redirect('/' . Yii::app()->user->loginUrl);
		}
	}
	
	/**
	 * Редирект пользователя для аутентификации
	 * См. фильтр CAccessControlFilter
	 */
	public function redirectToLogin()
	{
		Yii::app()->user->logout();
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