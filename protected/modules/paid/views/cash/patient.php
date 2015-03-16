<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $this->widget('PaidNavWidget'); ?>
<div class="container b-paid">
	<h4>Выбранный пациент: <?= $modelPatient->last_name; ?> <?= $modelPatient->first_name; ?> <?= $modelPatient->middle_name; ?></h4>
	<div class="row">
		<div class="col-xs-11">
			
		</div>
		<div class="col-xs-1">
			<?php $this->widget('PaidManageButtons'); ?>
		</div>
	</div>
</div>