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
	'emptyText'=>'',
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
				'class'=>'col-xs-1',
			],
		],
		[
			'name'=>'doctorName',
			'value'=>'$data->doctor->last_name . " " . $data->doctor->first_name . "" . $data->doctor->middle_name',
			'filter'=>CHtml::activeHiddenField($modelPaid_Order_Details, 'hash'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-1',
			],
		],
//			[
//				'name'=>'since_date',
//				'filter'=>false,
//				'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
//			],
//			[
//				'name'=>'exp_date',
//				'filter'=>false,
//				'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
//			],
//			[
//				'class'=>'CButtonColumn',
//				'template'=>'{view}',
//				'buttons'=>[
//					'view'=>[
//						'url'=>'CHtml::normalizeUrl(["cash/groups", "group_id"=>$data->paid_service_group_id])',
//						'imageUrl'=>false,
//						'label'=>'Перейти',
//						'options'=>[
//							'class'=>'btn btn-success btn-block btn-xs'
//						]
//					],
//					'headerHtmlOptions'=>[
//						'class'=>'col-xs-1',
//					],
//				],
//			],
	],
]);
