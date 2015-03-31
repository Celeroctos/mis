<?php
/**
 * Добавление группы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Добавление группы (подгруппы)</h4>
<?php $form=$this->beginWidget('CActiveForm', [
					'id'=>substr(md5(uniqid("", true)), 0, 7),
					'enableAjaxValidation'=>true,
					'enableClientValidation'=>true,
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
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service_Group, 'code', ['class'=>'control-label']); ?>
			<?= $form->TextField($modelPaid_Service_Group, 'code', [
							'class'=>'form-control input-sm',
						]); ?>
			<?= $form->error($modelPaid_Service_Group, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelDoctors, 'last_name', ['class'=>'control-labe']); ?>
			<div class="b-paid__checkBoxListDoctors">
				<?= $form->checkBoxList($modelDoctors, 'id', Doctors::getServiceGroupsListData()); ?>
				<?= $form->error($modelDoctors, 'id', ['class'=>'b-paid__errorFormServicesGroup']); ?>
			</div>
		</div>
			<?= $form->HiddenField($modelPaid_Service_Group, 'p_id', [
							'class'=>'form-control input-sm',
						]); ?>
		<div class="col-xs-4 col-xs-offset-4">
			<br>
			<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>