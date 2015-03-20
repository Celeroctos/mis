<?php
/**
 * Шаблон для работы с группами и услугами.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $this->widget('PaidNavWidget'); ?>
<div class="container b-paid b-paid_modificator">
	<div class="row b-paid__Row">
		<div class="col-xs-4 b-paid__borderRight">
			<?= Paid_Service_Groups::recursServicesOut(Paid_Service_Groups::model()->findAll('p_id=:p_id', ['p_id'=>0]), 0); ?>
			<?= CHtml::htmlButton('Добавить группу', ['class'=>'btn btn-sm btn-primary', 'id'=>'callModalAddGroup']); ?>
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
			'<h4>Пустой результат таблицы с услугами группы</h4>',
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
					'name'=>'price'
				],
				[
					'name'=>'since_date',
					'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', \'medium\')',
					],
				[
					'name'=>'exp_date',
					'value'=>'Yii::app()->dateFormatter->formatDateTime($data->since_date, \'medium\', \'medium\')',
				],
				[
					'class'=>'CButtonColumn',
					'template'=>'{view}',
					'buttons'=>[
						'view'=>[
							'imageUrl'=>false,
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
		?>
		</div>
		<div class="modal" id="modalAddGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content b-paid__modalHeader">
					<h4>Добавление группы</h4>
					<div class="modal-body" id="modalBodyAddGroup">
						<?php $form=$this->beginWidget('CActiveForm', [
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'true',
												'clientOptions'=>[
													'ajaxVar'=>'formAddGroup',
													'validateOnChange'=>true,
													'validateOnType'=>true,
													'validateOnSubmit'=>true,
												],
											]); ?>
							<div class="row">
								<div class="col-xs-4 col-xs-offset-4">
									<?= $form->Label($modelPaid_Service_Group, 'name', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service_Group, 'name', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $form->error($modelPaid_Service_Group, 'name', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									
									<?= $form->Label($modelPaid_Service_Group, 'code', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service_Group, 'code', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $form->error($modelPaid_Service_Group, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									<?= $form->HiddenField($modelPaid_Service_Group, 'p_id', [
													'class'=>'form-control input-sm',
													'value'=>0, //по умолчанию
												]); ?>
									<br>
									<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
								</div>
							</div>
							<?php $this->endWidget(); ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="modalAddServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content b-paid__modalHeader">
					<h4>Добавление услуги</h4>
					<div class="modal-body" id="modalBodyAddServices">
						<?php $form=$this->beginWidget('CActiveForm', [
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'true',
												'clientOptions'=>[
													'ajaxVar'=>'formAddServices',
													'validateOnChange'=>true,
													'validateOnType'=>true,
													'validateOnSubmit'=>true,
												],
											]); ?>
							<div class="row">
								<div class="col-xs-4 col-xs-offset-4">
									<?= $form->Label($modelPaid_Service, 'name', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service, 'name', ['class'=>'form-control input-sm',]); ?>
									<?= $form->error($modelPaid_Service, 'name', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									
									<?= $form->Label($modelPaid_Service, 'code', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service, 'code', ['class'=>'form-control input-sm',]); ?>
									<?= $form->error($modelPaid_Service, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									
									<?= $form->Label($modelPaid_Service, 'paid_service_group_id', ['class'=>'control-label']); ?>
									<?= $form->DropDownList($modelPaid_Service, 'paid_service_group_id', Paid_Service_Groups::getServiceGroupsListData(), ['class'=>'form-control input-sm']); ?>
									<?= $form->error($modelPaid_Service, 'paid_service_group_id', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									
									<?= $form->HiddenField($modelPaid_Service, 'paid_service_group_id', ['class'=>'form-control input-sm',]); ?>
									<br>
									<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
								</div>
							</div>
							<?php $this->endWidget(); ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>