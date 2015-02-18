<?php
// Проверка на наличие конфигурации соединения с БД
if (!file_exists(dirname(__FILE__).'/db/db.php')) 
{
	exit('Отсутствует конфигурация соединения с БД, см. protected/config/db/readme');
}

require_once('params.inc'); //доп. параметры.

return [
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Moniiag',
	'defaultController'=>'service/auth',

	// preloading 'log' component
	'preload'=>['log'],
	'homeUrl'=>['service/auth/login'],
	'sourceLanguage' => 'ru',
	'language' => 'ru',
	'charset'=>'utf-8',

	// autoloading model and component classes
	'import'=>[
		'application.models.*',
		'application.components.*',
		'application.widgets.*',
		'application.classes.*',
	],

	'modules'=>[
		'paid'=>[
			'defaultController' => 'cash',
			'layout' => 'main',
		],
		'service'=>[
			'defaultController' => 'auth',
			'layout' => 'main',
		],
	],

	'components'=>[
		
		'session'=>[
			'class'=>'CHttpSession',
			'autoStart'=>false,
		],
		
		'user'=>[
			'class'=>'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>['service/auth/login'], // module/controller/action, см. метод createUrl()
		],
		
		'clientScript' => include(dirname(__FILE__) . '/data/packages.php'),		

		'assets'=>[
			'class'=>'CAssetManager',
		],
		
		'db'=>include(dirname(__FILE__) . '/db/db.php'),
		
		'authManager'=>[
			'class'=>'PhpAuthManager',
			'defaultRoles' => ['guest'],
		],

		'urlManager'=>[
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>[
				//'<language:(ru|en)>' => 'main/index',
				],
			],

		'errorHandler'=>[
			'class'=>'ErrorHandler',
			//'errorAction'=>'service/error',
		],
		'log'=>[
			'class'=>'CLogRouter',
			'routes'=>[
				[
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				],
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			],
		],
	],
	// using Yii::app()->params['paramName']
	'params' => $params,
];