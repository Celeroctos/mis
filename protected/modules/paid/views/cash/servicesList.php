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
		</div>
		<div class="modal" id="paid_cash_servicesList-emptyGroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog b-modalAddServiceGroup">
				<div class="modal-content">
					<div class="modal-body" id="myModalBody">
						<?php $form=$this->beginWidget('CActiveForm', [
//												'id'=>'paid_cash_servicesList-emptyGroups',
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'t',
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
								<div class="col-xs-3">
									<?= $form->Label($modelPaid_Service_Group, 'name', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service_Group, 'name', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $form->error($modelPaid_Service_Group, 'name', []); ?>
								</div>
								<div class="col-xs-3">
									<?= $form->Label($modelPaid_Service_Group, 'code', ['class'=>'control-label']); ?>
									<?= $form->TextField($modelPaid_Service_Group, 'code', [
													'class'=>'form-control input-sm',
												]); ?>
									<?= $form->error($modelPaid_Service_Group, 'code', []); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<br>
									<?= CHtml::SubmitButton('Добавить группу', ['class'=>'btn btn-success btn-sm']); ?>
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