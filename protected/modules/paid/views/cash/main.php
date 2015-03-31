<?php
/**
 * Основное рабочее место кассира
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Касса';
?>
<div class="row">
	<div class="col-xs-10">
		<h3 class="b-paid__searchHeader">Поиск пациента</h3>
	<?php $form=$this->beginWidget('CActiveForm', [
					'id'=>'formSearchPatients',
					'enableAjaxValidation'=>true,
					'enableClientValidation'=>false,
					'clientOptions'=>[
						'validateOnChange'=>true,
						'validateOnType'=>true,
						'validationDelay'=>30,
						'ajaxVar'=>'formSearchPatients',
						'validateOnSubmit'=>true,
						'afterValidate'=>new CJavaScriptExpression("function(form, data, hasError) {
																	if(!hasError)
																	{
																		var action=$('#select_button').attr('name');
																		var url='/paid/cash/SearchPatientsResult';
																		switch(action)
																		{
																			case 'search':
																				url='/paid/cash/SearchPatientsResult';
																				break;
																			case 'create':
																				url='/paid/cash/CreatePatient';
																				break;
																		}
																		$.ajax({'data': $('#formSearchPatients').serialize(),
																				'url': url,
																				'type': 'POST',
																				'success': function (html) {
																					$('#modalSearchPatientBody').html(html);
																					$('#modalSearchPatient').modal('show');
																				}
																		});
																	}
																	return false; //нам не нужно отправлять эту форму.
																}"), //см paid.js
					],
	]); ?>
		<div class="row">
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'last_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'last_name', [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'last_name', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'first_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'first_name', [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'first_name', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'middle_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'middle_name', [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'middle_name', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'birthday', ['class'=>'control-label']); ?>
					<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
						'language'=>'ru',
						'model'=>$modelPatient,
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
							'class'=>'form-control',
						],
					]); ?>
				<?= $form->error($modelPatient, 'birthday', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'gender', ['class'=>'control-label']); ?>
				<?= $form->DropDownList($modelPatient, 'gender', Patients::getGenderListData(), [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'gender', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<?php $this->widget('FieldDocumentsWidget', ['model'=>$modelPatient_Documents, 'form'=>$form]); ?>
		<div class="row">
			<div class="col-xs-9">
					<?= $form->Label($modelPatient, 'address_reg', ['class'=>'control-label']); ?>
					<?= $form->TextField($modelPatient, 'address_reg', [
									'class'=>'form-control input-sm',
								]); ?>
					<?= $form->error($modelPatient, 'address_reg', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<?= $form->Label($modelPatient, 'snils', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'snils', [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'snils', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-3">
				<?= $form->Label($modelPaid_Medcard, 'paid_medcard_number', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPaid_Medcard, 'paid_medcard_number', [
								'class'=>'form-control input-sm',
							]); ?>
				<?= $form->error($modelPatient, 'paid_medcard_number', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-3">
				<?php $this->widget('FieldPhonesWidget', ['model'=>$modelPatient_Contacts, 'form'=>$form]); ?>
			</div>
		</div>
		<div class="row">
			<div id="select_button" name="search" class="col-xs-12"> <!-- какую кнопку нажали? -->
				<?= CHtml::SubmitButton('Найти', ['class'=>'btn btn-primary btn-sm',
												  'id'=>'submitSearchPatient',
												  'name'=>'search',
					]); ?>
				<?= CHtml::SubmitButton('Сохранить', ['class'=>'btn btn-success btn-sm',
													  'id'=>'submitCreatePatient',
													  'name'=>'create',
					]); ?>
			</div>
		</div>
	<?php $this->endWidget(); ?>
	</div>
</div>
<div class="modal" id="modalSearchPatient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSearchPacient">
		<div class="modal-content">
			<div class="modal-body" id="modalSearchPatientBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>