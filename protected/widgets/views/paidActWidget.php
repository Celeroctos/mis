<?php
/**
 * Шаблон действий виджета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="col-xs-2">
	<?= CHtml::ajaxButton('Возврат оплаты', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Выбрать счет', CHtml::normalizeUrl('/paid/cashAct/ChooseExpenses'), ['method'=>'POST', 'success'=>'chooseExpenses.selectExpenses'], ['class'=>'btn btn-default btn-block', 'id'=>'chooseExpenses']); ?>
</div>
<div class="col-xs-2 b-paid_grid_position" id="paidActWidget">
	<?= CHtml::link('К поиску', Yii::app()->controller->createUrl('cash/main'), ['class'=>'btn btn-default btn-block']); ?>
	<?= CHtml::ajaxButton('Выбрать услуги', CHtml::normalizeUrl('/paid/cashAct/SelectServices'), ['method'=>'POST', 'success'=>'selectServices'], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Отложить чек', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Вернуть чек', null, [], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
</div>