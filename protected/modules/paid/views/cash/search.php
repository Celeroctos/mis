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
		<div class="col-xs-10 b-paid__bodyLeft">
			<?php $form=$this->beginWidget('CActiveForm', ['id'=>'yw10']); ?>
				<?= $form->errorSummary($modelPatient, '', '', [
					'class'=>'alert alert-warning',
				]); ?>
				<?= $form->errorSummary($modelPaid_Medcard, '', '', [
					'class'=>'alert alert-warning',
				]); ?>
				<div class="row">
					<div class="col-xs-4">
							<?= $form->Label($modelPatient, 'last_name', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'last_name', [
											'class'=>'form-control input-sm',
										]); ?>

					</div>
					<div class="col-xs-4">
							<?= $form->Label($modelPatient, 'first_name', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'first_name', [
											'class'=>'form-control input-sm',
										]); ?>

					</div>
					<div class="col-xs-4">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'middle_name', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'middle_name', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'gender', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'gender', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'birthday', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'birthday', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'document_type', ['class'=>'control-label']); ?>
							<?= $form->DropDownList($modelPatient, 'document_type', $documentTypeListData, [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'document_serie', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'document_serie', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'document_number', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'document_number', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'document_who_gived', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'document_who_gived', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'document_date_gived', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'document_date_gived', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'address_reg', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'address_reg', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'snils', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'snils', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPaid_Medcard, 'paid_card_number', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPaid_Medcard, 'paid_card_number', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>	
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?= $form->Label($modelPatient, 'phone_number', ['class'=>'control-label']); ?>
							<?= $form->TextField($modelPatient, 'phone_number', [
											'class'=>'form-control input-sm',
										]); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<?= CHtml::ajaxSubmitButton('Найти', '', [
														'success'=>'function(html){
															$("#myModalBody").html(html);
															$("#myModal").modal("show");
														}',	
													], ['class'=>'btn btn-primary btn-sm',]); ?>
						<?= CHtml::SubmitButton('Сохранить', ['class'=>'btn btn-success btn-sm', 'id'=>'add_paid_patient_button', 'style'=>'display: none; opacity: 0;']); ?>
						<?= CHtml::hiddenField('scenario_ajax_create', '9', ['id'=>'add_paid_patient_input', 'style'=>'display: none;']); ?>
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
		<div class="col-xs-10 b-paid__footerLeft">
			<h3 class="b-paidFooterH3">Итого:</h3>
		</div>
		<div class="col-xs-2 b-paid__footerRight">
		</div>
	</div>
</div>
