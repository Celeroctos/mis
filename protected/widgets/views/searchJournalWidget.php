<?php
/**
 * Шаблон поиска по журналу.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $form=$this->beginWidget('CActiveForm', [
//	'action'=>CHtml::normalizeUrl(['journal/AjaxValidateSearchJournal']),
	'method'=>'post',
    'id'=>substr(md5(uniqid("", true)), 0, 7),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
]); ?>
<div class="row">
	<div class="col-xs-2">
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
					'placeholder'=>'Начальная дата',
				],
			]); ?>
		<?= $form->error($modelPaid_Expense, 'date', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
	<div class="col-xs-2">
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
					'placeholder'=>'Конечная дата',
				],
			]); ?>
		<?= $form->error($modelPaid_Expense, 'dateEnd', ['class'=>'b-paid__errorFormPatient']); ?>
	</div>
	<div class="col-xs-2">
		<?= CHtml::ajaxSubmitButton('Найти', '', ['method'=>'post', 'success'=>new CJavaScriptExpression('journal.ajaxSearch')], ['class'=>'btn btn-primary', 'id'=>substr(md5(uniqid("", true)), 0, 6)]); ?>
	</div>
</div>
<?php $this->endWidget(); ?>