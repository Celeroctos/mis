<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPatient->search(),
	'filter'=>$modelPatient,
	'id'=>'paid_grid_search_patients',
	'ajaxVar'=>'ajax_grid',
	'ajaxUpdate'=>true,
	'emptyText'=>'<div style="width: 185px; margin: 0 auto;">'
	. '<h4>Пациент не найден</h4>'
	. CHtml::htmlButton('Добавить', ['class'=>'btn btn-primary', 'id'=>'add_paid_modal_patient', 'name'=>'add_paid_modal_patient'])
	. ' <button type="button" class="btn btn-default" data-dismiss="modal">Назад</button>'
	. '</div>',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered',
	'pager'=>[
		'class'=>'CLinkPager',
		'cssFile'=>'',
		'selectedPageCssClass'=>'active',
		'firstPageCssClass'=>'',
//		'hiddenPageCssClass'=>'',
		'internalPageCssClass'=>'',
		'nextPageLabel'=>false,
		'prevPageLabel'=>false,
		'lastPageCssClass'=>'',
		'nextPageCssClass'=>'',
		'previousPageCssClass'=>'',
		'maxButtonCount'=>6,
		'selectedPageCssClass'=>'active',
		'header'=>false,
		'htmlOptions'=>[
			'class'=>'pagination',
		]
	],
	'columns'=>[
		[
			'name'=>'last_name',
			'filter'=>'',
			'headerHtmlOptions'=>[
				'class'=>'col-xs-7',
			],
		],
		[
			'name'=>'first_name',
			'filter' =>'',
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
					'options'=>[
						'class'=>'btn btn-success btn-block btn-xs'
					],
				],
				'headerHtmlOptions'=>[
					'class'=>'col-md-1',
				],
			],
		],
	],
]);