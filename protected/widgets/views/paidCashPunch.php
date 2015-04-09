<div class="row">
	<hr class="b-paid__hr">
	<div class="col-xs-8">
		<h3 class="b-paid__summHeader">Итого:</h3>
	</div>
	<div class="col-xs-4 b-paid__punchButtons">
		<?= CHtml::button('Отменить заказ', ['class'=>'btn btn-default', 'id'=>'deleteOrderButton', 'disabled'=>'disabled']); ?>
		<?= CHtml::button('Пробить', ['class'=>'btn btn-default', 'id'=>'punchButton', 'disabled'=>'disabled']); ?>
	</div>
</div>