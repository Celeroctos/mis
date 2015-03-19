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
			<?= CHtml::htmlButton('Добавить группу', ['class'=>'btn btn-block btn-success b-paid__buttonServiceGroupAdd', 'id'=>'paid_cash_servicesList-buttonEmptyGroups',]); ?>
		</div>
		<div class="modal" id="paid_cash_servicesList-modalAddGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" id="paid_cash_servicesList-modalAddGroupBody">
						<?php $formPopoverAddGroup=$this->beginWidget('CActiveForm', [
//												'id'=>'paid_cash_servicesList-emptyGroups',
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'true',
												'clientOptions'=>[
													'validateOnChange'=>false,
													'ajaxVar'=>'paid_cash_servicesList-emptyGroups',
													'validateOnSubmit'=>true,
													'afterValidate'=>new CJavaScriptExpression('function(form, data, hasError){
																									if(!hasError)
																									{
																										return true;
																									}
																							    }'),
												],
											]); ?>
							<div class="row">
								<div class="col-xs-4 col-xs-offset-4">
									<?= $formPopoverAddGroup->Label($modelPaid_Service_Group, 'name', ['class'=>'control-label']); ?>
									<?= $formPopoverAddGroup->TextField($modelPaid_Service_Group, 'name', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $formPopoverAddGroup->error($modelPaid_Service_Group, 'name', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									
									<?= $formPopoverAddGroup->Label($modelPaid_Service_Group, 'code', ['class'=>'control-label']); ?>
									<?= $formPopoverAddGroup->TextField($modelPaid_Service_Group, 'code', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $formPopoverAddGroup->error($modelPaid_Service_Group, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
									<?= $formPopoverAddGroup->HiddenField($modelPaid_Service_Group, 'p_id', [
													'class'=>'form-control input-sm',
												]); ?>
									<br>
									<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-success btn-sm']); ?>
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
		<div class="modal" id="paid_cash_servicesList-emptyGroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog b-modalAddServiceGroup">
				<div class="modal-content">
					<div class="modal-body" id="paid_cash_servicesList-emptyGroupsBody">
						<?php $form=$this->beginWidget('CActiveForm', [
//												'id'=>'paid_cash_servicesList-emptyGroups',
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'true',
												'clientOptions'=>[
													'validateOnChange'=>false,
													'ajaxVar'=>'paid_cash_servicesList-emptyGroups',
													'validateOnSubmit'=>true,
													'afterValidate'=>new CJavaScriptExpression('function(form, data, hasError){
																									if(!hasError)
																									{
																										return true;
																									}
																							    }'),
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
									<br>
									<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-success btn-sm']); ?>
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