<div class="row">
	<hr class="b-paid__hr">
	<div class="col-xs-4">
		<span class="b-paid__summHeader">Итого: <span id="TotalSum">0</span> руб.</span>
	</div>
	<div class="col-xs-4 col-xs-offset-4 b-paid__punchButtons">
		<div class="row">
			<div class="col-xs-12">
				<?= CHtml::button('Отменить заказ', ['class'=>'btn btn-default', 'id'=>'deleteOrderButton', 'disabled'=>'disabled']); ?>
				<?= CHtml::button('Пробить', ['class'=>'btn btn-default', 'id'=>'punchButton', 'disabled'=>'disabled']); ?>
			</div>
		</div>
		<table class="tabled table-bordered b-paid__tableMoney">
			<tr>
				<td>
					<span>Наличные:</span>
				</td>
				<td>
					<?= CHtml::textField('CashSum', '', []); ?>
				</td>
			</tr>
			<tr>
				<td>
					<span>Сдача:</span>
				</td>
				<td>
					<span></span>
				</td>
			</tr>
		</table>
	</div>
	<div class="errorOrderForm">
	</div>
</div>