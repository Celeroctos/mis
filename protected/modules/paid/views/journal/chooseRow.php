<?php
/**
 * Шаблон вывода строки журнала (модальное окно)
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="row">
	<div class="col-xs-12" style='text-align: center;'>
		<h4>Номер счёта: <i><?= $recordExpense->expense_number; ?>.</i></h4>
		<h4>Статус счёта: <i><?= $statusExpense ?></i>.</h4>
		<?= CHtml::button('Печать счёта', ['class'=>'btn btn-primary btn-sm', 'id'=>'printExpenseJournal']); ?>
		<?= CHtml::button('Печать договора', ['class'=>'btn btn-primary btn-sm', 'disabled'=>'disabled']); ?>
	</div>
</div>