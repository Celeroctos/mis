<?php
/**
 * Выбор неоплаченного счёта
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Выберите врача</h4>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPaid_Expenses->search(),
	'filter'=>$modelPaid_Expenses,
	'ajaxType'=>'post',
//	'id'=>'gridSelectExpenses',
	'id'=>$modelPaid_Expenses->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridSelectExpenses',
	'template'=>'{pager}{items}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>'',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered gridChooseExpenses',
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
			'name'=>'date',
			'filter'=>false,
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'expense_number',
			'filter'=>CHtml::activeHiddenField($modelPaid_Expenses, 'hash'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'patientName',
			'filter'=>false,
			'value'=>'$data->order->patient->last_name . " " . $data->order->patient->first_name . " " . $data->order->patient->middle_name',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'services',
			'value'=>'$data->getServices($data->paid_expense_id)',
			'filter'=>false,
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'price',
			'value'=>'ParseMoney::decodeMoney($data->price) . " руб."',
			'filter'=>false,
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
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