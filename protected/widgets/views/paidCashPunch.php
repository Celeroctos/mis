<div class="row">
	<hr class="b-paid__hr">
	<div class="col-xs-10">
		<h3 class="b-paid__summHeader">Итого:</h3>
	</div>
	<div class="col-xs-2">
		<?= CHtml::ajaxButton('Пробить', null, [], ['class'=>'btn btn-default btn-block', 'id'=>'punchButton', 'disabled'=>'disabled']); ?>
	</div>
</div>