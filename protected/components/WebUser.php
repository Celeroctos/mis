<?php
/**
 * Класс для работы с сущностью текущего авторизованного пользователя
 * @author Тайибов Джамал <prohps@yandex.ru>
 */
class WebUser extends CWebUser
{
	public $loginUrl='4ref';
	const MSG_SUCCESS = 'success';
	const MSG_ERROR = 'error';
	const MSG_INFO = 'info';
	const MSG_WARNING = 'warning';
	
	public function getFlashMessageKey($messageType)
	{
		switch ($messageType) {
			case self::MSG_SUCCESS:
				$key = 'success';
				break;
			case self::MSG_ERROR:
				$key = 'danger';
				break;
			case self::MSG_INFO:
				$key = 'info';
				break;
			case self::MSG_WARNING:
				$key = 'warning';
			default:
				$key = 'info';
				break;
		}
		return $key;
	}

	public function addFlashMessage($messageType, $message)
	{
		$key = $this->getFlashMessageKey($messageType);

		if ( $this->hasFlash($key) )
			$messages = $this->getFlash($key);
		else
			$messages = [];

		$messages[] = $message;

		$this->setFlash($key, $messages);
	}

	public function clearFlashMessages($messageType = null)
	{
		if (!isset($messageType)) {
			foreach ([ self::MSG_SUCCESS, self::MSG_ERROR, self::MSG_INFO ] as $messageType) {
				$this->clearFlashMessages($messageType);
			}
			return;
		}

		$key = $this->getFlashMessageKey($messageType);
		$this->setFlash($key, []);
	}

	/**
	 * Геттер для получения роли клиента.
	 * Пример использования: Yii::app()->user->role;
	 * @return string
	 */
	function getRole()
	{
		if(!Yii::app()->user->IsGuest)
		{
			$record=Users::model()->findByPk(Yii::app()->user->id);
			
			if($record!=null)
			{ //не существует такого пользователя.
				return $record->role_id;
			}
			else
			{
				return Yii::app()->authManager->defaultRoles[0];
			}
		}
		else
		{ //роль по умолчанию
			return Yii::app()->authManager->defaultRoles[0]; 
		}
	}
}