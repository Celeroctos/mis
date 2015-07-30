<?php
/**
 * Представление модали
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>

<div class="modal" id="transitionSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm b-modalTransitionSearch">
		<div class="modal-content b-modalTransitionSearch b-paid__modalHeader">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Выберите действие</h4>
			</div>
			<div class="modal-body" id="modalTransitionSearchBody">
				<div>
					<?= CHtml::button('Отложить счёт', ['class'=>'btn btn-primary', 'id'=>'transitionSearchOk']); ?>
					<?= CHtml::button('Отменить заказ', ['class'=>'btn btn-warning', 'id'=>'transitionSearchCancel']); ?>
				</div>
				<Br>
			</div>
<!--			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
			</div>-->
		</div>
	</div>
</div>