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
	'defaultController'=>'service/auth/login',

	// preloading 'log' component
	'preload'=>['log', 'params'],
	'homeUrl'=>'paid/cash/main',
	'sourceLanguage' => 'ru',
	'language' => 'ru',
	'charset'=>'utf-8',

	// autoloading model and component classes
	'import'=>[
		'application.models.*',
		'application.components.*',
		'application.widgets.*',
		'application.classes.*',
//		'ext.yii-pdf.*',
	],

	'modules'=>[
		'paid'=>[
			'defaultController' => 'cash',
			'layout' => 'main',
			'import'=>[
				'application.modules.paid.models.*',
			],
		],
		'service'=>[
			'defaultController' => 'auth',
			'layout' => 'main',
			'import'=>[
				'application.modules.service.models.*',
			],
		],
	],

	'components'=>[	
//		'session'=>[
//			'class'=>'CHttpSession',
//			'autoStart'=>false,
//		],
		
		'ePdf'=>[
			'class'=>'ext.yii-pdf.EYiiPdf',
			'params'=>[
				'mpdf'=>[
					'librarySourcePath'=>'application.vendor.mpdf57.*',
					'constants'=>[
						'_MPDF_TEMP_PATH'=>Yii::getPathOfAlias('application.runtime'),
					],
					'class'=>'mpdf',
				]
			],
		],
		
		'user'=>[
			'class'=>'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>'service/auth/login', // module/controller/action, см. метод createUrl()
		],
		
		'clientScript'=>include(dirname(__FILE__) . '/data/packages.php'),		

		'assets'=>[
			'class'=>'CAssetManager',
		],
		
		'db'=>include(dirname(__FILE__) . '/db/db.php'),
		
		'authManager'=>[
			'class'=>'PhpAuthManager',
			'defaultRoles' => [1], // 1=>'guest'
		],

		'urlManager'=>[
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>[
				//'<language:(ru|en)>' => 'main/index',
			],
		],

		'errorHandler'=>[
			'class'=>'CErrorHandler',
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