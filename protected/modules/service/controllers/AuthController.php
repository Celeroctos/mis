<?php
/**
 * Контроллер для управлением пользователями и их сессиями
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class AuthController extends MServiceController
{
	public function filters()
	{
		return ['accessControl',];
	}

	public function accessRules()
	{
		return [
			[
				'allow', //разрешить аутентификацию только анонимным юзерам.
				'controllers' => ['service/auth'],
				'users'=>['?'],
			],
			[
				'deny', //запрет всем остальным к регистрации/авторизации и перенаправление.
				'deniedCallback' => [$this, 'redirectToHomeUrl'],
				'controllers'    => ['service/auth'],
			],
		];
	}
	
	/**
	 * Экшн для аутентификации
	 */
	public function actionLogin()
	{
		$model=new Users('service.auth.login');
		
		if(isset($_POST['Users']))
		{
			$model->attributes=Yii::app()->request->getPost('Users');
			
			if($model->validate())
			{
				$model->login();
				echo Yii::app()->user->getRole();
			}
		}
		
		$this->render('login', [
			'model'=>$model,
		]);
	}
	
	/**
	 * Экшн выхода пользователя
	 */
	public function actionLogout()
	{
		if(!Yii::app()->user->IsGuest)
		{
			Yii::app()->user->logout();
			$this->redirect('/' . Yii::app()->user->loginUrl); //absolute url
		}
	}
	
	/**
	 * Экшн для регистрации новых пользователей
	 */
	public function actionRegister()
	{
		$model=new Users('service.auth.register');
		
		if(isset($_POST['Users']))
		{
			$model->attributes=Yii::app()->request->getPost('Users');
			$model->role_id=Users::ROLE_USER_ID;
			if($model->save())
			{
				Yii::app()->user->addFlashMessage(WebUser::MSG_SUCCESS, 'Вы успешно зарегистрировались. Теперь Вы можете войти в систему.');
				
				$model->password=''; //чистим Хэши паролей при ошибке в валидации.
				$model->passwordRepeat='';
				$this->redirect('/' . Yii::app()->user->loginUrl); //absolute url
			}
			else
			{
				$model->password=''; //чистим Хэши паролей при ошибке в валидации.
				$model->passwordRepeat='';
			}
		}
		
		$this->render('register', [
			'model'=>$model,
		]);
	}
	
	/**
	 * Главный экшн
	 * @return метод логина
	 */
	public function actionIndex()
	{
		return $this->actionLogin();
	}
	
	/**
	 * Экшн для восстановления пароля
	 */
	public function actionRestorePass()
	{
		
	}
}