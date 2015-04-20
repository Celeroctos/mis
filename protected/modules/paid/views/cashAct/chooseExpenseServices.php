<?php
/**
 * Выбор услуг по счёту
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Выберите услугу(и)</h4>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$dataProvider,
	'filter'=>$modelPaid_Order_Details,
	'ajaxType'=>'post',
//	'id'=>'gridSelectServices',
	'id'=>$modelPaid_Order_Details->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridChooseExpenseServices',
	'template'=>'{pager}{items}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>'Услуги в заказе отсутствуют!',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered gridChooseExpenseServices', //gridSelectServices используется в paid.js
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
			'class'=>'pagination pagination-sm b-paid__selectServicePagination',
		]
	],
	'columns'=>[
		[
			'name'=>'service.name',
			'filter'=>CHtml::activeHiddenField($modelPaid_Order_Details, 'hash'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'doctorName',
			'value'=>'$data->doctor->last_name . " " . $data->doctor->first_name . " " . $data->doctor->middle_name',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-6',
			],
		],
		[
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'buttons'=>[
				'delete'=>[
					'url'=>'CHtml::normalizeUrl(["cashAct/deleteExpenseService", "paid_order_detail_id"=>$data->paid_order_detail_id])',
					'imageUrl'=>false,
					'label'=>'Удалить',
					'options'=>[
						'class'=>'btn btn-danger btn-block btn-xs'
					]
				],
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
		],
	],
]);