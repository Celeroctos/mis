<?php
/**
 * Пакеты для подключения в CClientScript
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
define('_STATIC','static_src');

return [
	'packages'=>[
		'jquery'=>[
			'baseUrl'=>_STATIC . '/jquery/js/',
			'js'=>[
				'jquery-1.11.2.min.js',
			],
		],
		'header'=>[
			'baseUrl'=>_STATIC . '/header/',
			'css'=>[
				'css/header.css',
			],
		],
		'footer'=>[
			'baseUrl'=>_STATIC . '/footer/',
			'css'=>[
				'css/footer.css',
			],
		],
		'bootstrap'=>[
			'baseUrl'=>_STATIC . '/bootstrap-3.3.2-dist/',
			'css'=>[
				'css/bootstrap.min.css',
			],
			'js' => [
				'js/bootstrap.min.js',
			],
			'depends'=>[
				'jquery',
			],
		],
		'datetimepicker'=>[
			'baseUrl'=>_STATIC . '/datetimepicker/',
			'js'=>[
				'js/jquery.datetimepicker.js',
			],
			'css'=>[
				'css/jquery.datetimepicker.css',
			],
			'depends'=>[
				'jquery',
			],
		],
		//module paid
		'paid'=>[
			'baseUrl'=>_STATIC . '/paid/',
			'css'=>[
				'css/paid.css',
			],
			'js'=>[
				'js/paid.js',
			],
			'depends'=>[
				'bootstrap',
				'header',
				'footer',
			],
		],
		//module service
		'service'=>[
			'baseUrl'=>_STATIC . '/service/',
			'css'=>[
				'css/service.css',
			],
			'js'=>[
				'js/service.js',
			],
			'depends'=>[
				'bootstrap',
				'header',
				'footer',
			],
		],
	],
];