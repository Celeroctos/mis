<?php
/**
 * RBAC файл.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 * 
 * TYPE_ROLE - роль.
 * TYPE_OPERATION - операция.
 * TYPE_TASK - задача.
 */
return [
	//операции
	'authUser'=>[ //операция на проверку авторизованного пользователя ( обычный пользователь )
		'type'=>CAuthItem::TYPE_OPERATION,
		'description'=>'Проверка авторизации пользователя',
		'bizRule'=>null,
		'data'=>null,
	],

	'authPartner'=>[ //операция на проверку авторизованного пользователя ( партнёр )
		'type'=>CAuthItem::TYPE_OPERATION,
		'description'=>'Проверка авторизации партнёра',
		'bizRule'=>null,
		'data'=>null,
	],

	'authAdmin'=>[ //операция на проверку авторизованного пользователя ( админ )
		'type'=>CAuthItem::TYPE_OPERATION,
		'description'=>'Проверка авторизации админа',
		'bizRule'=>null,
		'data'=>null,
	],

	//роли
	Users::ROLE_GUEST_ID=>[
		'type'=>CAuthItem::TYPE_ROLE,
		'description'=>'guest',
		'bizRule' => null,
		'data' => null,
	],

	Users::ROLE_USER_ID=>[
		'type'=>CAuthItem::TYPE_ROLE,
		'description'=>'user',
		'children'=>[Users::ROLE_GUEST_ID, 'authUser'], //наследуем права и роль guest, добавляем к роли операцию
		'bizRule' => null,
		'data' => null,
	],
	
	Users::ROLE_ADMIN_ID=>[
		'type'=>CAuthItem::TYPE_ROLE,
		'description'=>'admin',
		'children'=>[Users::ROLE_GUEST_ID, 'authAdmin'],
		'bizRule' => null,
		'data' => null,
	],
];