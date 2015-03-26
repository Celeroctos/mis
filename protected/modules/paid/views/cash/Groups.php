<?php
/**
 * Шаблон для работы с группами и услугами.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $this->widget('PaidNavWidget'); ?>
<div class="container b-paid b-paid_modificator">
	<div class="row">
		<div class="col-xs-12">
			<?php $form=$this->beginWidget('CActiveForm', [
								'id'=>substr(md5(uniqid("", true)), 0, 7),
								'enableAjaxValidation'=>'true',
								'enableClientValidation'=>'true',
								'clientOptions'=>[
									'ajaxVar'=>'formSearchServices',
									'validationUrl'=>$this->createUrl('cash/searchServicesAjaxValidation'),
									'validateOnChange'=>true,
									'validateOnType'=>true,
									'validateOnSubmit'=>true,
									'afterValidate'=>new CJavaScriptExpression('afterValidate'), //см paid.js
								],
							]); ?>
			<div class="row">
				<div class="col-xs-3">
					<?= $form->Label($searchModelPaid_Service, 'code', ['class'=>'control-label']); ?>
					<?= $form->TextField($searchModelPaid_Service, 'code', ['class'=>'form-control input-sm',]); ?>
					<?= $form->error($searchModelPaid_Service, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
				</div>
				<div class="col-xs-3">
					<?= $form->Label($searchModelPaid_Service, 'paid_service_group_id', ['class'=>'control-label']); ?>
					<?= $form->TextField($searchModelPaid_Service, 'paid_service_group_id', ['class'=>'form-control input-sm',]); ?>
					<?= $form->error($searchModelPaid_Service, 'paid_service_group_id', ['class'=>'b-paid__errorFormServicesGroup']); ?>
				</div>
				<div class="col-xs-3">
					<?= $form->Label($searchModelPaid_Service, 'name', ['class'=>'control-label']); ?>
					<?= $form->TextField($searchModelPaid_Service, 'name', ['class'=>'form-control input-sm',]); ?>
					<?= $form->error($searchModelPaid_Service, 'name', ['class'=>'b-paid__errorFormServicesGroup']); ?>
				</div>
				<div class="col-xs-3">
					<?= CHtml::SubmitButton('Найти услугу', ['class'=>'btn btn-primary btn-sm b-paid__serviceSearchButton']); ?>
				</div>
			</div>
				
			<?php $this->endWidget(); ?>
		</div>
	</div>
	<div class="row b-paid__Row">
		<div class="col-xs-4 b-paid__borderRight">
			<?= Paid_Service_Groups::recursServicesOut(Paid_Service_Groups::model()->findAll('p_id=:p_id ORDER BY paid_service_group_id DESC', ['p_id'=>0]), 0); ?>
			<?= CHtml::htmlButton('Добавить группу', ['class'=>'btn btn-sm btn-primary', 'id'=>'buttonAddGroup']); ?>
		</div>
		<div class="col-xs-8">
		<?php
		$this->widget('zii.widgets.grid.CGridView', [
			'dataProvider'=>$modelPaid_Service->search(),
			'filter'=>$modelPaid_Service,
			'ajaxType'=>'post',
			'template'=>'{items}',
			'ajaxUpdate'=>true,
			'emptyText'=>
			'<h4 class="b-paid__emptyServiceHeader">Пустой результат</h4>',
			'showTableOnEmpty'=>false,
//			'itemsCssClass'=>'table table-bordered',
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
					'headerHtmlOptions'=>[
						'class'=>'col-xs-1',
					],
				],
				[
					'name'=>'code',
					'headerHtmlOptions'=>[
						'class'=>'col-xs-1',
					],
				],
				[
					'name'=>'price',
					'value'=>'ParseMoney::decodeMoney($data->price)',
				],
				[
					'name'=>'since_date',
					'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
				],
				[
					'name'=>'exp_date',
					'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', null)',
				],
				[
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
					'buttons'=>[
						'update'=>[
							'url'=>'CHtml::normalizeUrl(["cash/UpdateService", "id"=>$data->paid_service_id])',
							'imageUrl'=>false,
							'click'=>'updateService',
							'options'=>[
								'class'=>'btn btn-success btn-block btn-xs',
//								'id'=>'ajaxUpdateService', //см paid.js 
							],
						],
						'delete'=>[
							'url'=>'CHtml::normalizeUrl(["cash/deleteService", "id"=>$data->paid_service_id])',
							'imageUrl'=>false,
							'options'=>[
								'class'=>'btn btn-danger btn-block btn-xs',
							]
						],
						'headerHtmlOptions'=>[
							'class'=>'col-xs-1',
						],
					],
				],
			],
		]);
		?>
		</div>
	</div>
	<div class="modal" id="modalServiceGroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog b-modalServiceGroups">
			<div class="modal-content b-paid__modalHeader">
				<div class="modal-body" id="modalServiceGroupsBody">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</div>