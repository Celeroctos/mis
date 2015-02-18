<?php
/**
 * Авторизация пользователей.
 * @author: Тайибов Джамал <prohps@yandex.ru>
 */
class UserIdentity extends CUserIdentity
{
	protected $_id;

	public function authenticate()
	{
		$record=Users::model()->find('login=:login', [':login'=>$this->username]);

		if($record===null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif(!CPasswordHelper::verifyPassword($this->password, $record->password)
		&& md5(md5($this->password))!=$record->password) //обратная совместимость со старой версией приложения, где использовали md5(md5())
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			$this->_id = $record->id;
			$this->errorCode=self::ERROR_NONE;
		}

		return !$this->errorCode;
	}
	    
//	public function setLoginTime()
//	{
//		$record=Users::model()->findByPk($this->_id);
//		if ($record->firstLoginTimestamp === NULL)
//		{
//			$record->firstLoginTimestamp = strftime('%Y-%m-%d %H:%M:%S', time());
//			$record->save();
//		}
//		return 0;
//	}

	public function getId()
	{
		return $this->_id;
	}
}