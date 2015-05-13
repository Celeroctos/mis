<?php
/**
 * Шаблон редактирования пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<h4>Редактирование пациента</h4>
<?php $form=$this->beginWidget('CActiveForm', [
					'id'=>substr(md5(uniqid("", true)), 0, 7),
					'enableAjaxValidation'=>true,
					'enableClientValidation'=>false,
					'clientOptions'=>[
						'ajaxVar'=>'formUpdatePatient',
						'validateOnChange'=>true,
						'validateOnType'=>false,
						'validateOnSubmit'=>true,
					],
				]); ?>
<div class="row">
	<?= $form->HiddenField($modelPatient_Document, 'errorSummary', ['class'=>'form-control input-sm']); ?>
	<?= $form->HiddenField($modelPatient_Contact, 'value', ['class'=>'form-control input-sm']); ?>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'last_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($recordPatient, 'last_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'last_name', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'first_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($recordPatient, 'first_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'first_name', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'middle_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($recordPatient, 'middle_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'middle_name', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'gender', ['class'=>'control-label']); ?>
		<?= $form->DropDownList($recordPatient, 'gender', Patients::getGenderListData(), ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'gender', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'birthday', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$recordPatient,
				'attribute'=>'birthday',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
					'minDate'=>'1900-01-01',
				],
				'htmlOptions'=>[
					'class'=>'form-control birthday',
				],
			]); ?>
		<?= $form->error($recordPatient, 'birthday', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'address_reg', ['class'=>'control-label']); ?>
		<?= $form->TextField($recordPatient, 'address_reg', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'address_reg', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->Label($recordPatient, 'snils', ['class'=>'control-label']); ?>
		<?= $form->TextField($recordPatient, 'snils', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($recordPatient, 'snils', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4 b-contactUpdate">
		<?= CHtml::Label('Телефон(ы)', '', ['class'=>'control-label']); ?>
		<span class="b-phones__spanPlus glyphicon glyphicon-plus" aria-hidden="true"></span>
		<?php foreach($recordPatient_Contact as $contact) : ?>
			<div class="b-paid__contactUpdatePatient input-group">
				<?= CHtml::textField('Patient_Contacts[]', $contact['value'], ['class'=>'form-control input-sm', 'id'=>substr(uniqid(rand(1,9), true), 0, 5)]); ?>
				<span class="b-phones__spanDelete input-group-addon glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<?= $form->error($modelPatient_Contact, 'value', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
</div>
<div class="b-documentUpdate">
	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<?= CHtml::Label('Документ(ы)', null, ['class'=>'control-label']); ?>
			<span class="b-documentUpdate__spanPlus glyphicon glyphicon-plus" aria-hidden="true"></span>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<?= CHtml::Label('Тип документа', '', ['class'=>'control-label']); ?>
		</div>
		<div class="col-xs-4">
			<?= CHtml::Label('Серия', '', ['class'=>'control-label']); ?>
		</div>
		<div class="col-xs-3">
			<?= CHtml::Label('Номер', '', ['class'=>'control-label']); ?>
		</div>
	</div>
	<div class="row">
	<?php foreach($recordPatient_Document as $document) : ?>
		<div class="col-xs-4">
			<?= CHtml::dropDownList('Patient_Documents[type][]', $document['type'], Patients::getDocumentTypeListData(), ['class'=>'form-control input-sm', 'id'=>substr(uniqid(rand(1,9), true), 0, 5)]) ?>
		</div>
		<div class="col-xs-4">
			<?= CHtml::textField('Patient_Documents[serie][]', $document['serie'], ['class'=>'form-control input-sm', 'id'=>substr(uniqid(rand(1,9), true), 0, 5)]); ?>
		</div>
		<div class="col-xs-3">
			<?= CHtml::textField('Patient_Documents[number][]', $document['number'], ['class'=>'form-control input-sm', 'id'=>substr(uniqid(rand(1,9), true), 0, 5)]); ?>				
		</div>
		<div class="col-xs-1 b-documentUpdate__delete">
			<span class="b-documentUpdate__spanMinus glyphicon glyphicon-minus" aria-hidden="true"></span>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<?= $form->error($modelPatient_Document, 'errorSummary', ['class'=>'b-paid__errorFormPatient']); ?>		
	</div>
</div>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<br>
		<?= CHtml::SubmitButton('Редактировать', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>