<?php
/**
 * Основное рабочее место кассира
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Касса';
?>
<div class="row b-paid_position">
	<div class="col-xs-10">
		<h3 class="b-paid__searchHeader">Поиск пациента</h3>
	<?php $form=$this->beginWidget('CActiveForm', [
					'id'=>'formSearchPatients',
					'enableAjaxValidation'=>true,
					'enableClientValidation'=>false,
					'clientOptions'=>[
						'validateOnChange'=>true,
						'validateOnType'=>false,
						'validationDelay'=>30,
						'ajaxVar'=>'formSearchPatients',
						'validateOnSubmit'=>true,
						'afterValidate'=>new CJavaScriptExpression("function(form, data, hasError) {
																	if(hasError)
																	{
																		$('#submitSearchPatient').val('Найти');
																		$('#submitSearchPatient').animate({opacity: 1}, 50);
																	}
																	if(!hasError)
																	{
																		var action=$('#select_button').attr('name');
																		var url='/paid/cash/SearchPatientsResult';
																		var success=function () {};
																		switch(action)
																		{ // смотрим какое действие было совершено (поиск или создание)
																			case 'search':
																				url='/paid/cash/SearchPatientsResult';
																				success=function (html) {
																					$('#modalSearchPatientBody').html(html);
																					$('#submitSearchPatient').val('Найти');
																		$('#submitSearchPatient').animate({opacity: 1}, 50);
																					$('#modalSearchPatient').modal('show');
																				};
																				break;
																			case 'create':
																				url='/paid/cash/CreatePatient';
																				success=function (html) {
																					if(html!=0)
																					{
																						location.href='/paid/cash/patient/patient_id/' + html;
																					}
																				};
																				break;
																		}
																		$.ajax({'data': $('#formSearchPatients').serialize(),
																				'url': url,
																				'type': 'POST',
																				'success': success, //функция из switch()
																		}); //и делаем запрос в зависимости от действия
																	}
																	return false; //нам не нужно отправлять эту форму.
																}"), //см paid.js
					],
	]); ?>
		<div class="row">
			<div class="col-xs-12">
				<?= $form->errorSummary($modelPatient, null, null, ['class'=>'alert alert-warning']); ?>
				<?= $form->HiddenField($modelPatient, 'errorSummary', ['class'=>'form-control input-sm']); ?>
				
				<?= $form->errorSummary($modelPatient_Documents, null, null, ['class'=>'alert alert-warning']); ?>
				<?= $form->HiddenField($modelPatient_Documents, 'errorSummary', ['class'=>'form-control input-sm']); ?>
				
				<?= $form->errorSummary($modelPatient_Contacts, null, null, ['class'=>'alert alert-warning']); ?>
				<?= $form->HiddenField($modelPatient_Contacts, 'errorSummary', ['class'=>'form-control input-sm']); ?>				
			</div>
			<div style="display: none">
				<?= $form->error($modelPatient, 'errorSummary', ['class'=>'b-paid__errorFormPatient']); ?>
				<?= $form->error($modelPatient_Documents, 'errorSummary', ['class'=>'b-paid__errorFormPatient']); ?>
				<?= $form->error($modelPatient_Contacts, 'errorSummary', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<?= $form->Label($modelPatient, 'last_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'last_name', ['class'=>'form-control input-sm',]); ?>
				<?= $form->error($modelPatient, 'last_name', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-4">
				<?= $form->Label($modelPatient, 'first_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'first_name', ['class'=>'form-control input-sm',]); ?>
				<?= $form->error($modelPatient, 'first_name', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-4">
				<?= $form->Label($modelPatient, 'middle_name', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'middle_name', ['class'=>'form-control input-sm',]); ?>
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
				<?= $form->DropDownList($modelPatient, 'gender', Patients::getGenderListData(), ['class'=>'form-control input-sm',]); ?>
				<?= $form->error($modelPatient, 'gender', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<?php $this->widget('FieldDocumentsWidget', ['model'=>$modelPatient_Documents, 'form'=>$form]); ?>
		<div class="row">
			<div class="col-xs-12">
					<?= $form->Label($modelPatient, 'address_reg', ['class'=>'control-label']); ?>
					<?= $form->TextField($modelPatient, 'address_reg', ['class'=>'form-control input-sm',]); ?>
					<?= $form->error($modelPatient, 'address_reg', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<?= $form->Label($modelPatient, 'snils', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPatient, 'snils', ['class'=>'form-control input-sm',]); ?>
				<?= $form->error($modelPatient, 'snils', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-4">
				<?= $form->Label($modelPaid_Medcard, 'paid_medcard_number', ['class'=>'control-label']); ?>
				<?= $form->TextField($modelPaid_Medcard, 'paid_medcard_number', ['class'=>'form-control input-sm',]); ?>
				<?= $form->error($modelPatient, 'paid_medcard_number', ['class'=>'b-paid__errorFormPatient']); ?>
			</div>
			<div class="col-xs-4">
				<?php $this->widget('FieldPhonesWidget', ['model'=>$modelPatient_Contacts, 'form'=>$form]); ?>
			</div>
		</div>
		<div class="row">
			<div id="select_button" name="search" class="col-xs-12"> <!-- какую кнопку нажали? (search or create) -->
				<?= CHtml::SubmitButton('Найти', ['class'=>'btn btn-primary', 'data-loading-text'=>'Загрузка..', 'data-role'=>'button', 'id'=>'submitSearchPatient', 'name'=>'search',]); ?>
				<?= CHtml::SubmitButton('Сохранить', ['class'=>'btn btn-success','id'=>'submitCreatePatient','name'=>'create',]); ?>
			</div>
		</div>
	<?php $this->endWidget(); ?>
	</div>
	<?php $this->widget('PaidActWidget', ['modelPatient'=>$modelPatient]); ?>
</div>

<?php $this->widget('PaidCashPunch'); ?>

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