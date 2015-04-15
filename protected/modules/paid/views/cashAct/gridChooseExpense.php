<?php
/**
 * Выбор неоплаченного счёта
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Выберите врача</h4>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPaid_Expense->search(),
	'filter'=>$modelPaid_Expense,
	'ajaxType'=>'post',
//	'id'=>'gridSelectServices',
	'id'=>$modelPaid_Expense->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridSelectExpense',
	'template'=>'{pager}{items}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>
	'<h4 class="b-paid__emptyServiceHeader">Счета отсутствуют.</h4>',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered gridChooseDoctor', //gridSelectServices используется в paid.js
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
			'name'=>'expense_number',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'date',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'date',
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