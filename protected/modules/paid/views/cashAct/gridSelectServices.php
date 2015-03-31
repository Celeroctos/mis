<?php
/**
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'dataProvider'=>$modelPaid_Service->search(),
	'filter'=>$modelPaid_Service,
	'ajaxType'=>'post',
//	'id'=>'gridSelectServices',
	'id'=>$modelPaid_Service->hash, //сохраняем ID при обновлении ajax
	'ajaxVar'=>'gridSelectServices',
	'template'=>'{pager}{items}',
	'ajaxUpdate'=>true,
	'enableSorting'=>false,
	'emptyText'=>
	'<h4 class="b-paid__emptyServiceHeader">По данным критериям услуги не найдены!</h4>',
	'showTableOnEmpty'=>false,
	'itemsCssClass'=>'table table-bordered gridSelectServices', //gridSelectServices используется в paid.js
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
				'name'=>'code',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'name',
				'filter'=>CHtml::activeHiddenField($modelPaid_Service, 'code') .
						  CHtml::activeHiddenField($modelPaid_Service, 'paid_service_group_id') .
						  CHtml::activeHiddenField($modelPaid_Service, 'name') .
						  CHtml::activeHiddenField($modelPaid_Service, 'hash'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-4',
				],
			],
			[
				'name'=>'group.name',
				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-3',
				],
			],
			[
				'name'=>'price',
				'filter'=>false,
				'value'=>'ParseMoney::decodeMoney($data->price)',
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
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
?>
<h4>Выбранные услуги</h4>
<table id="tableSelectionServices" class="table table-bordered table-striped">
	<thead>
		<th>
			Код услуги
		</th>
		<th>
			Название
		</th>
		<th>
			Название группы
		</th>
		<th>
			Цена
		</th>
<!--		<th>
			Действует с
		</th>
		<th>
			Действует до
		</th>-->
		<th>
			Удаление
		</th>
	</thead>
	<tbody>
		<tr class="empty">
			<td colspan="7"><span>Выберите услуги</span></td>
		</tr>
	</tbody>
</table>