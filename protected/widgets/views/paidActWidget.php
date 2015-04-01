<?php
/**
 * Шаблон действий виджета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="col-xs-2">
	<?= CHtml::ajaxButton('Возврат оплаты', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Выбрать счет', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
</div>
<div class="col-xs-2 b-paid_grid_position" id="paidActWidget">
	<?= CHtml::link('К поиску', Yii::app()->controller->createUrl('cash/main'), ['class'=>'btn btn-default btn-block',]); ?>
	<?= CHtml::ajaxButton('Выбрать услуги', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Отложить чек', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Вернуть чек', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
</div>