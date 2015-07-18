<?php
/**
 * Выбор услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Выберите услуги</h4>
<br>
<?php $form=$this->beginWidget('CActiveForm', [
	'method'=>'post',
    'id'=>substr(md5(uniqid("", true)), 0, 7),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
]); ?>
<div class="row form-group">
	<div class="col-xs-4">
		<?= $form->Label($modelPaid_Service, 'code', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPaid_Service, 'code', ['class'=>'form-control input-sm',]); ?>
	</div>
	<div class="col-xs-4">
		<?= $form->Label($modelPaid_Service, 'name', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPaid_Service, 'name', ['class'=>'form-control input-sm',]); ?>
	</div>
	<div class="col-xs-4">
		<?= $form->Label($modelPaid_Service->modelPaid_Service_Groups, 'name', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPaid_Service->modelPaid_Service_Groups, 'name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->HiddenField($modelPaid_Service, 'hash'); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<?= CHtml::button('Применить фильтр', ['class'=>'btn btn-primary btn-sm', 'id'=>'selectServicesFilter']); ?>
		<?= CHtml::button('Очистить', ['class'=>'btn btn-primary btn-sm', 'id'=>'cleanSelectServicesFilter']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>

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
//	'enableSorting'=>false,
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
				'type'=>'raw',
				'value'=>'"<div class=\"serviceId\">" . $data->paid_service_id . "</div><div class=\"codeService\">" . $data->code . "</div>"',
//				'filter'=>false,
				'headerHtmlOptions'=>[
					'class'=>'col-xs-1',
				],
			],
			[
				'name'=>'name',
				'filter'=>CHtml::activeTextField($modelPaid_Service, 'name') . 
						  CHtml::activeHiddenField($modelPaid_Service, 'hash'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-4',
				],
			],
			[
				'name'=>'group.name',
				'filter'=>CHtml::activeTextField($modelPaid_Service->modelPaid_Service_Groups, 'name'),
				'headerHtmlOptions'=>[
					'class'=>'col-xs-3',
				],
			],
			[
				'name'=>'price',
				'filter'=>false,
				'type'=>'raw',
				'value'=>'"<div class=\"priceService\">" . ParseMoney::decodeMoney(CHtml::encode($data->price)) . "</div>"',
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
<table id="tablePrepareOrderServices" class="table table-bordered table-striped">
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
		<th>
			Врач
		</th>
<!--		<th>
			Действует с
		</th>
		<th>
			Действует до
		</th>-->
		<th class="prepareOrderServicesHeadRemove">
			Удаление
		</th>
	</thead>
	<tbody>
<!--		<tr class="empty">
			<td colspan="7"><span>Пусто</span></td>
		</tr>-->
	</tbody>
</table>