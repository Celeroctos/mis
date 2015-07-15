<?php
/**
 * Шаблон действий виджета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="col-xs-2 paidActWidget">
	<?= CHtml::link(Yii::app()->controller->action->id==='patient' ? 'К поиску' : 'Очистить форму', Yii::app()->controller->createUrl('cash/main'), ['class'=>'btn btn-default btn-block']); ?>
	<?= CHtml::button('Возврат оплаты', ['class'=>'btn btn-default btn-block', 'id'=>'returnPayment', 'disabled'=>'disabled']); ?>
	<?= CHtml::ajaxButton('Выбрать счет', CHtml::normalizeUrl(['/paid/cashAct/ChooseExpenses', 'patient_id'=>$modelPatient->patient_id]), ['method'=>'POST', 'success'=>'chooseExpenses.selectExpenses'], ['class'=>'btn btn-default btn-block', 'id'=>'chooseExpenses', 'disabled'=>'disabled']); ?>
</div>
<div class="col-xs-2 b-paid_grid_position paidActWidget">
	<?= CHtml::ajaxButton('Выбрать услуги', CHtml::normalizeUrl('/paid/cashAct/SelectServices'), ['method'=>'POST', 'success'=>'selectServices'], ['class'=>'btn btn-default btn-block', 'disabled'=>'disabled']); ?>
	<?= CHtml::button('Выбрать услуги (new)', ['class'=>'btn btn-default btn-block', 'id'=>'beginPrepareOrder']); ?>
</div>
<div class="modal" id="modalSelectServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectServices">
		<div class="modal-content b-modalContentSelectServices b-paid__modalHeader">
			<div class="modal-body" id="modalSelectServicesBody">
			</div>
			<div class="modal-footer">
				<button type="button" id="prepareOrderConfirm" class="btn btn-primary" data-dismiss="modal">Сформировать заказ</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalSelectDoctors" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectDoctors">
		<div class="modal-content b-modalContentSelectDoctors b-paid__modalHeader">
			<div class="modal-body" id="modalSelectDoctorsBody">
			</div>
			<div class="modal-footer">
				<!--<button type="button" id="selectedServicesConfirm" class="btn btn-primary" data-dismiss="modal">Сформировать заказ</button>-->
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>