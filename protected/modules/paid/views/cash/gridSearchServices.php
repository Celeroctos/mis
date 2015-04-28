<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPaid_Service->search(),
	'filter'=>$modelPaid_Service,
	'ajaxType'=>'post',
	'id'=>$modelPaid_Service->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridSearchServices',
	'template'=>'{items}{pager}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>
	'<h4 class="b-paid__emptyServiceHeader">' . $modelPaid_Service->emptyTextGrid . '</h4>',
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
				'name'=>'name',
				'filter'=>CHtml::activeHiddenField($modelPaid_Service, 'code') .
						  CHtml::activeHiddenField($modelPaid_Service, 'paid_service_group_id') .
						  CHtml::activeHiddenField($modelPaid_Service, 'name') .
						  CHtml::activeHiddenField($modelPaid_Service, 'hash'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'code',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'group.name',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'price',
				'filter'=>false,
				'value'=>'ParseMoney::decodeMoney($data->price)',
			],
			[
				'name'=>'since_date',
				'filter'=>false,
				'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
			],
			[
				'name'=>'exp_date',
				'filter'=>false,
				'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
			],
			[
				'class'=>'CButtonColumn',
				'template'=>'{view}',
				'buttons'=>[
					'view'=>[
						'url'=>'CHtml::normalizeUrl(["cash/groups", "group_id"=>$data->paid_service_group_id])',
						'imageUrl'=>false,
						'label'=>'Перейти',
						'options'=>[
							'class'=>'btn btn-success btn-block btn-xs'
						]
					],
					'headerHtmlOptions'=>[
						'class'=>'col-xs-1',
					],
				],
			],
	],
]);