<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="row b-paid_position">
	<div class="col-xs-10">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$modelPatient,
			'nullDisplay'=>'Пациент не выбран!',
			'attributes'=>[
				[
					'name'=>'last_name',
					'htmlOptions'=>'col-xs-1',
				],
				'first_name',        // an attribute of the related object "owner"
				'middle_name',
				'address_reg',
			],
		)); ?>
		
		<h4 class="b-paid__selectHeader">Выбранные услуги</h4>
		
		<?php $this->widget('zii.widgets.grid.CGridView', [
		'dataProvider'=>$modelPaid_Service->search(),
		'filter'=>$modelPaid_Service,
		'ajaxType'=>'post',
	//	'id'=>$modelPaid_Service->hash, //сохраняем ID при обновлении ajax
		'ajaxVar'=>'gridSearchServices',
		'template'=>'{items}{pager}',
		'ajaxUpdate'=>true,
		'enableSorting'=>false,
	//	'emptyText'=>
	//	'<h4 class="b-paid__emptyServiceHeader">По данным критериям услуги не найдены!</h4>',
	//	'showTableOnEmpty'=>false,
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
						'class'=>'col-xs-3',
					],
				],
				[
					'name'=>'group.name',
					'filter'=>false,
					'headerHtmlOptions'=>[
						'class'=>'col-xs-2',
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
		],
	]);
	?>
	</div>
	<?php $this->widget('PaidActWidget'); ?>
</div>
<?php $this->widget('PaidCashPunch'); ?>
<div class="modal" id="modalSelectServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectServices">
		<div class="modal-content b-paid__modalHeader">
			<div class="modal-body" id="modalSelectServicesBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>