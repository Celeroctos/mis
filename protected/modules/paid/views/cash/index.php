<?php
/**
 * Шаблон кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle="Касса";
?>
<div class="container b-paidNav">
	<div class="row">
		<nav>
			<ul class="nav nav-pills nav-justified">
				<li role="presentation" class="b-paidNav__li active"><a class="b-paidNav__href" href="#">Касса</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Журнал</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Отчёты</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Прайс</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Настройки</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Пациенты</a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="container b-paid">
	<div class="row">
		<div class="col-xs-11 b-paid__bodyLeft">
			<?php $form=$this->beginWidget('CActiveForm', [
												'id'=>'paid_cash_search-form',
												'enableAjaxValidation'=>'true',
												'enableClientValidation'=>'false',
												'clientOptions'=>[
													'validateOnChange'=>false,
													'ajaxVar'=>'paid_cash_search-form',
													'validateOnSubmit'=>true,
													'afterValidate'=>new CJavaScriptExpression('function(form, data, hasError){
																									if(!hasError)
																									{
																										window.location.replace(data.redirectUrl);
																									}
																							    }'),
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
					<div class="col-xs-1">
						<?= $form->Label($modelPatient, 'gender', ['class'=>'control-label']); ?>
						<?= $form->DropDownList($modelPatient, 'gender', $genderListData, [
										'class'=>'form-control input-sm',
									]); ?>
						<?= $form->error($modelPatient, 'gender', ['class'=>'b-paid__errorFormPatient']); ?>
					</div>
					<div class="col-xs-2">
							<?= $form->Label($modelPatient, 'birthday', ['class'=>'control-label']); ?>
								<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
									'language'=>'ru',
									'model'=>$modelPatient,
									'attribute'=>'birthday',
									'options'=>[
										'changeMonth'=>'true',
										'changeYear'=>'true',
//										'showButtonPanel'=>true,
										'showOn'=>'focus', // 'focus', 'button', 'both'
										'dateFormat'=>'yy-mm-dd',
										'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
										'minDate'=>'1900-01-01',
//										'maxDate'=>Yii::app()->dateformatter->format('yy-MM-dd', time()),
									],
									'htmlOptions'=>[
										'class'=>'form-control',
									],
								]); ?>
							<?= $form->error($modelPatient, 'birthday', ['class'=>'b-paid__errorFormPatient']); ?>
					</div>
				</div>
			
				<?php $this->widget('FieldDocumentsWidget', ['model'=>$modelPatient_Documents, 'form'=>$form, 'documentTypeListData'=>$documentTypeListData]); ?>
				
				<div class="row">
					<div class="col-xs-8">
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
					<div class="col-xs-12">
						<?= CHtml::ajaxSubmitButton('Найти', '', ['data'=>new CJavaScriptExpression('jQuery(this).parents("form").serialize() + "&paid_cash_search_patient_ajax=1"'),
																  
																  'success'=>'function(html){
																				$("#myModalBody").html(html);
																				$("#add_paid_patient_button").animate({opacity: 0}, "fast", function(){
																					$("#add_paid_patient_button").css("display", "none");
																				});
																				$("#myModal").modal("show");
																			  }',
																 ], 
																 ['class'=>'btn btn-primary btn-sm',]
													); ?>
						<?= CHtml::SubmitButton('Сохранить', ['class'=>'btn btn-success btn-sm', 'id'=>'add_paid_patient_button', 'name'=>'add_paid_patient_button', 'style'=>'display: none; opacity: 0;']); ?>
					</div>
					<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog b-modalSearchPacient">
							<div class="modal-content">
								<div class="modal-body" id="myModalBody">
									...
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php $this->endWidget(); ?>
		</div>
		<div class="col-xs-2 b-paid__bodyRight">
		</div>
	</div>
	<div class="row b-paidFooterRow">
		<div class="col-xs-11 b-paid__footerLeft">
			<h3 class="b-paidFooterH3">Итого:</h3>
		</div>
		<div class="col-xs-2 b-paid__footerRight">
		</div>
	</div>
</div>