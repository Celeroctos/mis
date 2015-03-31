<?php
/**
 * Шаблон для добавления услуги.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Добавление услуги</h4>
<?php $form=$this->beginWidget('CActiveForm', [
						'id'=>substr(md5(uniqid("", true)), 0, 7),
						'enableAjaxValidation'=>true,
						'enableClientValidation'=>true,
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
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service, 'code', ['class'=>'control-label']); ?>
			<?= $form->TextField($modelPaid_Service, 'code', ['class'=>'form-control input-sm',]); ?>
			<?= $form->error($modelPaid_Service, 'code', ['class'=>'b-paid__errorFormServicesGroup']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service, 'price', ['class'=>'control-label']); ?>
			<?= $form->TextField($modelPaid_Service, 'price', ['class'=>'form-control input-sm']); ?>
			<?= $form->error($modelPaid_Service, 'price', ['class'=>'b-paid__errorFormServicesGroup']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service, 'since_date', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Service,
				'attribute'=>'since_date',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
//					'showButtonPanel'=>true,
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'2000:'.Yii::app()->dateformatter->format('yyyy', time()),
//					'minDate'=>'2000-01-01',
//					'maxDate'=>Yii::app()->dateformatter->format('yy-MM-dd', time()),
				],
				'htmlOptions'=>[
					'class'=>'form-control',
				],
			]); ?>
			<?= $form->error($modelPaid_Service, 'since_date', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service, 'exp_date', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Service,
				'attribute'=>'exp_date',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
//					'showButtonPanel'=>true,
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'2000:'.Yii::app()->dateformatter->format('yyyy', time()),
//					'minDate'=>'2000-01-01',
//					'maxDate'=>Yii::app()->dateformatter->format('yy-MM-dd', time()),
				],
				'htmlOptions'=>[
					'class'=>'form-control',
				],
			]); ?>
			<?= $form->error($modelPaid_Service, 'exp_date', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->Label($modelPaid_Service, 'reason', ['class'=>'control-label']); ?>
			<?= $form->TextField($modelPaid_Service, 'reason', ['class'=>'form-control input-sm']); ?>
			<?= $form->error($modelPaid_Service, 'reason', ['class'=>'b-paid__errorFormServicesGroup']); ?>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<?= $form->checkBoxList($modelDoctors, 'id', Doctors::getServiceGroupsListData()); ?>
			<?= $form->error($modelDoctors, 'id', ['class'=>'b-paid__errorFormServicesGroup']); ?>
		</div>
			<?= $form->HiddenField($modelPaid_Service, 'paid_service_group_id', ['class'=>'form-control input-sm',]); ?>
		<div class="col-xs-4 col-xs-offset-4">
			<br>
			<?= CHtml::SubmitButton('Добавить', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>