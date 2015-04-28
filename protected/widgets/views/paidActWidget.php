<?php
/**
 * Шаблон действий виджета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="col-xs-2 paidActWidget">
	<?= CHtml::link('К поиску', Yii::app()->controller->createUrl('cash/main'), ['class'=>'btn btn-default btn-block']); ?>
	<?= CHtml::button('Возврат оплаты', ['class'=>'btn btn-default btn-block', 'id'=>'returnPayment', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Выбрать счет', CHtml::normalizeUrl(['/paid/cashAct/ChooseExpenses', 'patient_id'=>$modelPatient->patient_id]), ['method'=>'POST', 'success'=>'chooseExpenses.selectExpenses'], ['class'=>'btn btn-default btn-block', 'id'=>'chooseExpenses', 'disabled'=>'disabled']); ?>
</div>
<div class="col-xs-2 b-paid_grid_position paidActWidget">
	<?= CHtml::ajaxButton('Выбрать услуги', CHtml::normalizeUrl('/paid/cashAct/SelectServices'), ['method'=>'POST', 'success'=>'selectServices'], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
</div>