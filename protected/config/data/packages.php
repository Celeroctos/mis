<?php
/**
 * Пакеты для подключения в CClientScript
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */

define('_STATIC', $params['_STATIC']);

return [
	'packages'=>[
		'jquery-ui'=>[
			'baseUrl'=>_STATIC . '/jquery-ui/js/',
			'js'=>['jquery-ui.min.js', 'jquery-ui-i18n.min.js'] //берем из Yii
		],
		'jquery'=>[
			'baseUrl'=>_STATIC . '/jquery/js/',
			'js'=>[
				'jquery-1.11.2.min.js',
			],
		],
		'jquery.inputmask'=>[
			'baseUrl'=>_STATIC .'/jquery.inputmask/js/',
			'js'=>[
				'jquery.inputmask.js',
			],
			'depends'=>[
				'jquery',
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
		'fieldPhonesWidget'=>[
			'baseUrl'=>_STATIC . '/widgets/fieldPhones/',
			'js'=>[
				'js/fieldPhones.js'
			],
			'css'=>[
				'css/fieldPhones.css'
			],
		],
		'fieldDocumentsWidget'=>[
			'baseUrl'=>_STATIC . '/widgets/fieldDocuments',
			'js'=>[
				'js/fieldDocuments.js',
			],
			'css'=>[
				'css/fieldDocuments.css'
			]
		],
		//module paid
		'paid'=>[
			'baseUrl'=>_STATIC . '/paid/',
			'css'=>[
				'css/paid.css',
			],
			'js'=>[
				'js/paid.js',
				'js/returnPayment.js',
			],
			'depends'=>[
				'bootstrap',
				'header',
				'footer',
			],
		],
		'journal'=>[
			'baseUrl'=>_STATIC . '/paid/',
			'js'=>[
				'js/journal.js',
			],
			'depends'=>[
				'bootstrap',
				'jquery'
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