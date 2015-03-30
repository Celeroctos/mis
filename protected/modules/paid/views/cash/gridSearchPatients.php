<?php
/**
 * Шаблон таблицы поиска пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPatient->search(),
	'filter'=>$modelPatient,
	'id'=>$modelPaid_Service->hash, //сохраняем ID при обновлении ajax
	'ajaxType'=>'post',
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
			'filter'=>  CHtml::activeHiddenField($modelPatient->modelPatient_Documents, 'serie') .
						CHtml::activeHiddenField($modelPatient, 'last_name') .
						CHtml::activeHiddenField($modelPatient, 'first_name') .
						CHtml::activeHiddenField($modelPatient, 'middle_name') .
						CHtml::activeHiddenField($modelPatient, 'last_name') .
						CHtml::activeHiddenField($modelPatient, 'gender') .
						CHtml::activeHiddenField($modelPatient, 'birthday') .
						CHtml::activeHiddenField($modelPatient, 'address_reg') .
						CHtml::activeHiddenField($modelPatient, 'snils') .
						CHtml::activeHiddenField($modelPatient->modelPatient_Documents, 'type') .
						CHtml::activeHiddenField($modelPatient->modelPatient_Documents, 'serie') .
						CHtml::activeHiddenField($modelPatient->modelPatient_Documents, 'number') .
						CHtml::activeHiddenField($modelPatient->modelPaid_Medcard, 'paid_medcard_number') .
						CHtml::activeHiddenField($modelPatient->modelPatient_Contacts, 'value'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-2',
			],
		],
		[
			'name'=>'first_name',
			'filter'=>false,
		],
		[
			'name'=>'middle_name',
			'filter'=>false,
		],
		[
			'name'=>'gender',
			'filter'=>false,
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
				'class'=>'col-xs-2',
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