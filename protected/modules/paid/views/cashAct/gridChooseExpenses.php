<?php
/**
 * Выбор неоплаченного счёта
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Неоплаченные счета пациента</h4>
<?php $form=$this->beginWidget('CActiveForm', [
											'id'=>'formSearchExpenses',
											'enableAjaxValidation'=>true,
											'enableClientValidation'=>true,
											'clientOptions'=>[
//												'validateOnChange'=>true,
//												'validateOnType'=>true,
//												'validationDelay'=>30,
												'ajaxVar'=>'formSearchExpenses',
												'validateOnSubmit'=>true,
												'afterValidate'=>new CJavaScriptExpression('chooseExpenses.afterValidateSearchExp'),
											]
]); ?>
	<div class="row">
		<div class="col-xs-3">
			<?= $form->Label($modelPaid_Expenses, 'date', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Expenses,
				'attribute'=>'date',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
					'minDate'=>'1900-01-01',
//					'maxDate'=>Yii::app()->dateformatter->format('yy-MM-dd', time()),
				],
				'htmlOptions'=>[
					'class'=>'form-control input-sm',
				],
			]); ?>
			<?= $form->error($modelPaid_Expenses, 'date', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-3">
			<?= $form->Label($modelPaid_Expenses, 'dateEnd', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Expenses,
				'attribute'=>'dateEnd',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
					'showOn'=>'focus',
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
					'minDate'=>'1900-01-01',
				],
				'htmlOptions'=>[
					'class'=>'form-control input-sm',
				],
			]); ?>
			<?= $form->error($modelPaid_Expenses, 'dateEnd', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-2">
			<?= CHtml::label('Поиск', '', ['class'=>'control-label', 'style'=>'opacity: 0;']); ?>
			<?= CHtml::submitButton('Поиск', ['id'=>$modelPaid_Expenses->hashForm, 'class'=>'btn btn-block btn-primary btn-sm']); ?>
		</div>
		<div class="col-xs-3">
			<?= CHtml::label('Очистить', '', ['class'=>'control-label', 'style'=>'opacity: 0']); ?>
			<?= CHtml::button('Очистить', ['id'=>'cleanChooseExpenses', 'class'=>'btn btn-block btn-default btn-sm']); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>
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
	'emptyText'=>'Счета не найдены.',
//	'showTableOnEmpty'=>false,
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
			'value'=>'Yii::app()->dateformatter->format("yyyy-MM-dd HH:mm", $data->date);',
			'filter'=>CHtml::activeTextField($modelPaid_Expenses, 'date') . CHtml::activeTextField($modelPaid_Expenses, 'dateEnd'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-3',
			],
		],
		[
			'name'=>'expense_number',
			'type'=>'raw',
			'value'=>'"<div class=\"expense_number\">". CHtml::encode($data->expense_number) . "</div>"',
			'filter'=>CHtml::activeTextField($modelPaid_Expenses, 'expense_number') . CHtml::activeTextField($modelPaid_Expenses, 'hash'),
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
		[
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'buttons'=>[
				'delete'=>[
					'url'=>'CHtml::normalizeUrl(["cashAct/deleteExpense", "paid_expense_id"=>$data->paid_expense_id])',
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