<?php
/**
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Выберите врача</h4>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$dataProvider,
	'filter'=>$modelDoctors,
	'ajaxType'=>'post',
//	'id'=>'gridSelectServices',
	'id'=>$modelDoctors->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridSelectDoctor',
	'template'=>'{pager}{items}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>
	'<h4 class="b-paid__emptyServiceHeader">Врач(и) не найден(ы)</h4>',
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
			'name'=>'id',
			'type'=>'raw',
			'visible'=>false,
			'value'=>'"<div class=\"id\">" . $data->id . "</div>"',
			'filter'=>false,
			'headerHtmlOptions'=>[
				'class'=>'col-xs-1',
			],
		],
		[
			'name'=>'last_name',
			'type'=>'raw',
			'value'=>'"<div class=\"doctorId\">" . $data->id . "</div>" . "<div class=\"lastName\">" . $data->last_name . "</div>"',
			'filter'=>false,
			'headerHtmlOptions'=>[
				'class'=>'col-xs-1',
			],
		],
		[
			'name'=>'first_name',
			'type'=>'raw',
			'value'=>'"<div class=\"firstName\">" . $data->first_name . "</div>"',
			'filter'=>CHtml::activeHiddenField($modelDoctors, 'hash'),
			'headerHtmlOptions'=>[
				'class'=>'col-xs-4',
			],
		],
		[
			'name'=>'middle_name',
			'type'=>'raw',
			'value'=>'"<div class=\"middleName\">" . $data->middle_name . "</div>"',
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