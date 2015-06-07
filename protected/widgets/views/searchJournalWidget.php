<?php
/**
 * Шаблон поиска по журналу.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $form=$this->beginWidget('CActiveForm', [
	'action'=>CHtml::normalizeUrl(['journal/AjaxValidateSearchJournal']),
    'id'=>'searchJournal',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
	'clientOptions'=>[
//		'validateOnChange'=>false,
		'validateOnType'=>false,
//		'validationDelay'=>30,
		'ajaxVar'=>'searchJournal',
		'validateOnSubmit'=>true,
		'afterValidate'=>new CJavaScriptExpression('journal.searchAfterValidate'),
	],
]); ?>
<div class="row">
	<div class="col-xs-2">
		<?= $form->Label($modelPaid_Expense, 'date', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Expense,
				'attribute'=>'date',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
					'minDate'=>'1900-01-01',
					'maxDate'=>Yii::app()->dateformatter->format('yyyy-MM-dd', time()),
				],
				'htmlOptions'=>[
					'class'=>'form-control',
				],
			]); ?>
		<?= $form->error($modelPaid_Expense, 'date', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
	<div class="col-xs-2">
		<?= $form->Label($modelPaid_Expense, 'dateEnd', ['class'=>'control-label']); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'language'=>'ru',
				'model'=>$modelPaid_Expense,
				'attribute'=>'dateEnd',
				'options'=>[
					'changeMonth'=>'true',
					'changeYear'=>'true',
					'showOn'=>'focus', // 'focus', 'button', 'both'
					'dateFormat'=>'yy-mm-dd',
					'yearRange'=>'1900:'.Yii::app()->dateformatter->format('yyyy', time()),
					'minDate'=>'1900-01-01',
					'maxDate'=>Yii::app()->dateformatter->format('yyyy-MM-dd', time()),
				],
				'htmlOptions'=>[
					'class'=>'form-control',
				],
			]); ?>
		<?= $form->error($modelPaid_Expense, 'dateEnd', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
	<div class="col-xs-2">
		<?= CHtml::submitButton('Найти', ['class'=>'btn btn-primary']); ?>
	</div>
</div>
<!--
<div class="row">
	<div class="col-xs-3">
		<?= $form->Label($modelPatient, 'last_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPatient, 'last_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($modelPatient, 'last_name'); ?>
	</div>
	<div class="col-xs-3">
		<?= $form->Label($modelPatient, 'first_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPatient, 'first_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($modelPatient, 'first_name'); ?>
	</div>
	<div class="col-xs-3">
		<?= $form->Label($modelPatient, 'middle_name', ['class'=>'control-label']); ?>
		<?= $form->TextField($modelPatient, 'middle_name', ['class'=>'form-control input-sm',]); ?>
		<?= $form->error($modelPatient, 'middle_name'); ?>
	</div>
</div>-->

<?php $this->endWidget(); ?>