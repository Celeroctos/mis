<?php
/**
 * Контроллер для управлением пользователями и их сессиями
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class AuthController extends MServiceController
{
	/**
	 * Экшн для авторизации
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
			}
		}
		
		$this->render('login', [
			'model'=>$model,
		]);
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
				Yii::app()->user->addFlashMessage(WebUser::MSG_SUCCESS, 'Вы успешно зарегистрировались!');
				Yii::app()->controller->refresh();
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