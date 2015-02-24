<?php
/**
 * Шаблон аутентификации пользователя
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle="Вход в систему";
?>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading">Вход в систему</div>
			<div class="panel-body">
				<div class="form">
					<?php $form=$this->beginWidget('CActiveForm', ['id'=>'user-form',
																		 'enableClientValidation'=>true,]); ?>
						<div class="row">
							<div class="col-xs-12">
								<?= $form->errorSummary($model, '', '', [
									'class'=>'alert alert-warning',
								]); ?>
							</div>
						</div>
						<div class="form-group">
								<?= $form->Label($model, 'login', ['class'=>'control-label']); ?>
								<?= $form->TextField($model, 'login', [
												'class'=>'form-control',
											]); ?>
						</div>
						<div class="form-group">
								<?= $form->Label($model, 'password', ['class'=>'control-label']); ?>
								<?= $form->PasswordField($model, 'password', [
												'class'=>'form-control',
											]); ?>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<?= CHtml::submitButton('Войти', ['class'=>'btn btn-primary btn-block btn-sm',]); ?>
								<?= CHtml::link('Создать пользователя', Yii::app()->createUrl('service/auth/register'), ['class'=>'btn btn-default btn-block btn-sm']); ?>
							</div>
						</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</div>
</div>