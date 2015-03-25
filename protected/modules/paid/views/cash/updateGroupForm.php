<?php
/**
 * Редактирование группы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Редактирование группы (подгруппы)</h4>
<?php $form=$this->beginWidget('CActiveForm', [
					'id'=>substr(md5(uniqid("", true)), 0, 7),
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
			
			<?= $form->Label($modelPaid_Service_Group, 'p_id', ['class'=>'control-label']); ?>
			<?= $form->DropDownList($modelPaid_Service_Group, 'p_id', Paid_Service_Groups::getServiceGroupsListData(), [
							'class'=>'form-control input-sm',
						]); ?>
			<?= $form->error($modelPaid_Service_Group, 'p_id', ['class'=>'b-paid__errorFormServicesGroup']); ?>
			<br>
			<?= CHtml::SubmitButton('Редактировать', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>