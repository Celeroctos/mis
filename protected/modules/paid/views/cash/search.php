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
							<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Прайс-лист</a></li>
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
								<?= $form->errorSummary($model, '', '', [
									'class'=>'alert alert-warning',
								]); ?>
								<div class="form-inline">
									<div class="row">
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($model, 'last_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($model, 'last_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($model, 'first_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($model, 'first_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($model, 'middle_name', ['class'=>'control-label']); ?>
												<?= $form->TextField($model, 'middle_name', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="form-inline">
									<div class="row">
										<div class="col-xs-3">
											<div class="form-group">
												<?= $form->Label($model, 'birthday', ['class'=>'control-label']); ?>
												<?= $form->TextField($model, 'birthday', [
																'class'=>'form-control input-sm',
															]); ?>
											</div>					
										</div>
										<div class="col-xs-3">

										</div>
										<div class="col-xs-3">

										</div>
										<div class="col-xs-3">

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