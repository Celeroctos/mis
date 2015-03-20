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
			<?= Paid_Service_Groups::recursServicesOut(Paid_Service_Groups::model()->findAll('p_id=:p_id', ['p_id'=>0]), 0); ?>
			<?= CHtml::htmlButton('Добавить группу', ['class'=>'btn btn-block btn-primary b-paid__buttonServiceGroupAdd', 'id'=>'callModalAddGroup',]); ?>
		</div>
		<div class="modal" id="modalAddGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content b-paid__modalAddGroupHeader">
					<div class="modal-header">
						<h5>Добавление группы</h5>
					</div>
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
	</div>
</div>