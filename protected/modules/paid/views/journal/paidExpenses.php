<?php
/**
 * Шаблон оплаченных счетов
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Оплаченные счета';
?>
<?php $this->widget('NavJournalWidget'); ?>
<div class="b-content__journal">
	
	<?php $this->widget('SearchExpenseWidget', ['modelPaid_Expense'=>$modelPaid_Expenses, 'modelPatient'=>$modelPatient]); ?>
	
	<h4>Оплаченные счета</h4>
	<?php
	$this->widget('zii.widgets.grid.CGridView', [
		'dataProvider'=>$dataProvider,
		'filter'=>$modelPaid_Expenses,
		'ajaxType'=>'post',
	//	'id'=>'gridSelectExpenses',
		'id'=>$modelPaid_Expenses->hash, //сохраняем ID при обновлении ajax
		'ajaxVar'=>'gridSelectExpenses',
		'template'=>'{pager}{items}',
		'ajaxUpdate'=>true,
		'enableSorting'=>false,
		'emptyText'=>'Cчета отсутствуют!',
//		'showTableOnEmpty'=>false,
		'itemsCssClass'=>'table table-bordered gridJournalExpenses',
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
				'value'=>'Yii::app()->dateformatter->formatDateTime($data->date, "medium");',
				'filter'=>CHtml::activeTextField($modelPaid_Expenses, 'date') . CHtml::activeTextField($modelPaid_Expenses, 'dateEnd'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-2',
				],
			],
			[
				'name'=>'expense_number',
				'type'=>'raw',
				'value'=>'"<div class=\"expense_number\">". CHtml::encode($data->expense_number) . "</div>"',
				'filter'=>CHtml::activeTextField($modelPaid_Expenses, 'expense_number') . CHtml::activeTextField($modelPaid_Expenses, 'hash'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'patientName',
				'filter'=>CHtml::activeTextField($modelPatient, 'last_name') . CHtml::activeTextField($modelPatient, 'first_name') . CHtml::activeTextField($modelPatient, 'middle_name'),
				'value'=>'$data->order->patient->last_name . " " . $data->order->patient->first_name . " " . $data->order->patient->middle_name',
				'headerHtmlOptions'=>[
					'class'=>'col-xs-2',
				],
			],
			[
				'name'=>'services',
				'value'=>'$data->getServices($data->paid_expense_id)',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-2',
				],
			],
			[
				'name'=>'price',
				'value'=>'ParseMoney::decodeMoney($data->price) . " руб."',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'Doctors',
				'value'=>'$data->getDoctors($data->paid_expense_id)',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-3',
				]
			],
			[
				'name'=>'status',
				'value'=>'$data->getStatus($data->status)',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-2',
				]
			],
//			[
//				'class'=>'CButtonColumn',
//				'template'=>'{delete}',
//				'buttons'=>[
//					'delete'=>[
//						'url'=>'CHtml::normalizeUrl(["cashAct/deleteExpense", "paid_expense_id"=>$data->paid_expense_id])',
//						'imageUrl'=>false,
//						'label'=>'Удалить',
//						'options'=>[
//							'class'=>'btn btn-danger btn-block btn-xs'
//						]
//					],
//					'headerHtmlOptions'=>[
//						'class'=>'col-xs-1',
//					],
//				],
//			],
		],
	]);
	?>
</div>