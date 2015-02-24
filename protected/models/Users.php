<?php
/**
 * Users AR
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Users extends ActiveRecord
{
	public $id;
	public $username;
	public $login;
	public $password;
	public $role_id;
	
	public $verifyCode;
	public $passwordRepeat;
	
	const ROLE_GUEST_ID = 1;
	const ROLE_GUEST_NAME = 'guest';
	const ROLE_ADMIN_ID = 2;
	const ROLE_ADMIN_NAME = 'admin';
	const ROLE_USER_ID = 3;
	const ROLE_USER_NAME = 'user';
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'mis.users';
	}
	
	public function rules()
	{
		return [
			//Сценарии аутентификации в системе
			['login, password', 'required', 'on'=>'service.auth.login'],
			['login, password', 'type', 'type'=>'string', 'on'=>'service.auth.login'],
			['login', 'checkUserIdentity', 'on'=>'service.auth.login'],
			/**********************************/
			
			//Сценарии регистрации в системе (порядок правил не менять!)
			['login', 'unique', 'on'=>'service.auth.register'],
			['role_id', 'type', 'type'=>'integer', 'on'=>'service.auth.register'],
			['username, login, password, passwordRepeat', 'required', 'on'=>'service.auth.register'],
			['login, password', 'length', 'min'=>5, 'on'=>'service.auth.register'],
			['password', 'compare', 'compareAttribute'=>'passwordRepeat', 'on'=>'service.auth.register'],
			['username, login, password, passwordRepeat', 'type', 'type'=>'string', 'on'=>'service.auth.register'],
			['password', 'filter', 'filter'=>['Users', 'filterPasswordToHash'], 'on'=>'service.auth.register'],
			/*******************************/
		];
	}
	
	/**
	 * Валидатор-фильтр, преобразует пароль (и повтор пароля) в hash
	 */
	public function filterPasswordToHash($value)
	{
		return CPasswordHelper::hashPassword($value, 4);
	}
	
	/**
	 * Валидатор аутентификации пользователя
	 */
	public function checkUserIdentity($attribute)
	{
		$record=Users::model()->find('login=:login', [':login'=>$this->login]);
		
		if($record===null)
		{
			$this->addError($attribute, 'Неверный логин или пароль');
			return 0;
		}
		
		if(!CPasswordHelper::verifyPassword($this->password, $record->password)
		&& md5(md5($this->password))!=$record->password) //обратная совместимость со старой версией приложения, где использовали md5(md5())
		{
			$this->addError($attribute, 'Неверный логин или пароль');
			return 0;
		}
	}
	
	/**
	 * Аутентификация пользователя
	 */
	public function login()
	{
		$identity=new UserIdentity($this->login, $this->password);
		
		if($identity->authenticate())
		{
			Yii::app()->user->login($identity);
		}
		else
		{
//			Yii::app()->user->addFlashMessage(WebUser::MSG_ERROR, 'Неверно введен логин или пароль!');
		}
	}
	
	/**
	 * Заголовки полей
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'id'=>'ID',
			'username'=>'ФИО',
			'login'=>'Логин',
			'password'=>'Пароль',
			'passwordRepeat'=>'Повтор пароля',
		];
	}
}