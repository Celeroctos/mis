<?php
/**
 * Шаблон таблицы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxType'=>'post',
	'template'=>'{items}',
//	'ajaxVar'=>'ajax_servicesListGrid',
	'ajaxUpdate'=>true,
	'ajaxUrl'=>CHtml::normalizeUrl('fsdfsd/fsdfsdfds'),
	'emptyText'=>'<div style="width: 185px; margin: 0 auto;">'
	. '<h4>Пациент не найден!</h4>'
	. CHtml::htmlButton('Добавить услугу', ['class'=>'btn btn-xs btn-block btn-primary', 'id'=>'add_paid_modal_patient', 'name'=>'add_paid_modal_patient'])
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
			'name'=>'name',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-10',
			],
		],
		[
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'buttons'=>[
				'view'=>[
					'imageUrl'=>false,
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