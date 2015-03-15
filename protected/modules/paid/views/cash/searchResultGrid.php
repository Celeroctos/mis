<?php
/**
 * Шаблон таблицы поиска пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPatient->search(),
	'filter'=>$modelPatient,
	'id'=>'paid_cash_search-gridPatients',
	'ajaxType'=>'post',
	'ajaxVar'=>'ajax_grid',
	'ajaxUpdate'=>true,
	'emptyText'=>'<div style="width: 185px; margin: 0 auto;">'
	. '<h4>Пациент не найден!</h4>'
	. CHtml::htmlButton('Добавить', ['class'=>'btn btn-block btn-primary', 'id'=>'add_paid_modal_patient', 'name'=>'add_paid_modal_patient'])
	. '</div>',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered',
	'pager'=>[
		'class'=>'CLinkPager',
		'cssFile'=>'',
		'selectedPageCssClass'=>'active',
		'firstPageCssClass'=>'',
		'hiddenPageCssClass'=>'',
		'internalPageCssClass'=>'',
		'nextPageLabel'=>false,
		'prevPageLabel'=>false,
		'lastPageCssClass'=>'',
		'nextPageCssClass'=>'',
		'maxButtonCount'=>'7',
		'previousPageCssClass'=>'',
		'selectedPageCssClass'=>'active',
		'header'=>false,
		'htmlOptions'=>[
			'class'=>'pagination',
		]
	],
	'columns'=>[
		[
			'name'=>'last_name',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-2',
			],
		],
		[
			'name'=>'first_name',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-2',
			],
		],
		[
			'name'=>'middle_name',
		],
		[
			'name'=>'gender',
			'visible'=>false,
		],
		[
			'name'=>'documents.type',
			'visible'=>false,
		],
		[
			'name'=>'documents.serie',
			'visible'=>false,
		],
		[
			'name'=>'documents.number',
			'visible'=>false,
		],
		[
			'name'=>'address_reg',
			'visible'=>false,
		],
		[
			'name'=>'snils',
			'visible'=>false,
		],
		[
			'name'=>'paid_medcards.paid_medcard_number',
			'visible'=>false,
		],
		[
			'name'=>'contacts.value',
			'visible'=>false,
		],
		[
			'name'=>'birthday',
			'filter'=>'',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'buttons'=>[
				'view'=>[
					'label'=>'Выбрать пациента',
					'imageUrl'=>false,
					'url'=>'CHtml::normalizeUrl(["cash/index", "patient_id"=>$data->patient_id])',
					'options'=>[
						'class'=>'btn btn-success btn-block btn-xs'
					],
				],
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
		],
	],
]);