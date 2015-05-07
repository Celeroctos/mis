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
					'enableClientValidation'=>true,
					'clientOptions'=>[
						'ajaxVar'=>'formUpdatePatient',
						'validateOnChange'=>true,
						'validateOnType'=>false,
						'validateOnSubmit'=>true,
					],
				]); ?>
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<br>
		<?= CHtml::SubmitButton('Редактировать', ['class'=>'btn btn-block btn-primary btn-sm']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>