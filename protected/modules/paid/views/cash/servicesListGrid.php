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
	'ajaxUpdate'=>true,
	'emptyText'=>
	'<h5 class="b-paid__emptyServiceHeader">У данной группы услуги не найдены!</h5>',
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
				'class'=>'col-xs-1',
			],
		],
		[
			'name'=>'code',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-1',
			],
		],
		[
			'name'=>'price'
		],
		[
			'name'=>'since_date',
			'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', \'medium\')',
			],
		[
			'name'=>'exp_date',
			'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', \'medium\')',
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