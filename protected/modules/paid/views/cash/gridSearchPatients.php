<?php
/**
 * Шаблон таблицы поиска пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPatient->search(),
	'filter'=>$modelPatient,
	'id'=>$modelPatient->hash, //сохраняем ID при обновлении ajax
	'ajaxType'=>'post',
	'ajaxVar'=>'gridSearchPatients',
	'ajaxUpdate'=>true,
	'emptyText'=>'<div style="font-size: 17px; text-align: center;">'
	. $modelPatient->emptyText
	. '<br>'
	. CHtml::htmlButton('Добавить пациента', ['class'=>'btn btn-primary', 'id'=>'gridCreatePatient'])
	. '</div>',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered gridSearchPatients',
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
			'filter'=>  CHtml::activeHiddenField($modelPatient, 'hash') .
						CHtml::activeHiddenField($modelPatient->modelPatient_Documents, 'serie') .
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
					'url'=>'CHtml::normalizeUrl(["cash/patient", "patient_id"=>$data->patient_id])',
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