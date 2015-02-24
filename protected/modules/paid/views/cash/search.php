<?php
/**
 * Шаблон кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle="Касса";
?>
<div class="b-paid">
	<div class="b-paidHeader">
		<div class="container">
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1">
					<div class="b-paidNav">
						<ul class="nav nav-pills nav-justified">
							<li role="presentation" class="b-paidNav__li active"><a class="b-paidNav__href" href="#">Касса</a></li>
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Журнал</a></li>
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Отчёты</a></li>
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Прайс</a></li>
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Настройки</a></li>
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Пациенты</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="b-paidBody">
			<div class="row">
					<div class="col-xs-10">
						<div class="b-paidBody__left">
							<?php $form=$this->beginWidget('CActiveForm'); ?>
								<?= $form->errorSummary($modelPatient, '', '', [
									'class'=>'alert alert-warning',
								]); ?>
								<?= $form->errorSummary($modelPaid_Medcard, '', '', [
									'class'=>'alert alert-warning',
								]); ?>
								<div class="form-inline">
									<div class="row">
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($modelPatient, 'last_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($modelPatient, 'last_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($modelPatient, 'first_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($modelPatient, 'first_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($modelPatient, 'middle_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($modelPatient, 'middle_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
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
							<?php $this->endWidget(); ?>
						</div>
					</div>
					<div class="col-xs-2">
						<div class="b-paidBody__right">
						</div>
					</div>
			</div>
		</div>
	</div>
</div>