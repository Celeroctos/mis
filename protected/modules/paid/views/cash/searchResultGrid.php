<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPatient->search(),
	'filter'=>$modelPatient,
	'ajaxVar'=>'ajax',
	'ajaxUpdate'=>true,
	'itemsCssClass'=>'table table-bordered',
	'pager'=>[
		'class'=>'CLinkPager',
		'selectedPageCssClass'=>'active',
		'header'=>'',
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
			'buttons'=>[
				'view'=>[
					'label'=>'Просмотр',
					'imageUrl'=>false,
					'options'=>[
						'class'=>'btn btn-primary btn-block btn-xs'
					],
				],
				'update'=>[
					'label'=>'Редактировать',
					'imageUrl'=>false,
					'options'=>[
						'class'=>'btn btn-primary btn-block btn-xs'
					],
				],
				'delete'=>[
					'label'=>'Удалить',
					'imageUrl'=>false,
					'options'=>[
						'class'=>'btn btn-default btn-block btn-xs'
					],
				],
				'headerHtmlOptions'=>[
					'class'=>'col-md-1',
				],
			],
		],
	],
]);