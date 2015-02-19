<?php
/**
 * Шаблон регистрации новых пользователей
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle="Регистрация";
?>
<h3>Регистрация</h3>
<div class="form-horizontal">
	<?php $form=$this->beginWidget('CActiveForm'); ?>
		<div class="row">
			<div class="col-xs-12">
				<?= $form->errorSummary($model, '', '', [
					'class'=>'alert alert-warning',
				]); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-3">
				<?= $form->Label($model, 'username', ['class'=>'control-label']); ?>
			</div>
			<div class="col-xs-5">
				<?= $form->TextField($model, 'username', [
								'class'=>'form-control input-sm',
							]); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-3">
				<?= $form->Label($model, 'login', ['class'=>'control-label']); ?>
			</div>
			<div class="col-xs-5">
				<?= $form->TextField($model, 'login', [
								'class'=>'form-control input-sm',
							]); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-3">
				<?= $form->Label($model, 'password', ['class'=>'control-label']); ?>
			</div>
			<div class="col-xs-5">
				<?= $form->PasswordField($model, 'password', [
								'class'=>'form-control input-sm',
							]); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-3">
				<?= $form->Label($model, 'passwordRepeat', ['class'=>'control-label']); ?>
			</div>
			<div class="col-xs-5">
				<?= $form->PasswordField($model, 'passwordRepeat', [
								'class'=>'form-control input-sm',
							]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-3">
				<?= CHtml::submitButton('Зарегистрироваться',['class'=>'btn btn-primary btn-sm', ]); ?>
			</div>
		</div>
	<?php $this->endWidget(); ?>
</div>