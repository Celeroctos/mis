<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<?php $this->widget('PaidNavWidget'); ?>
<div class="container b-paid">
	<h4>Выбранный пациент: <?= CHtml::encode($modelPatient->last_name); ?> <?= CHtml::encode($modelPatient->first_name); ?> <?= CHtml::encode($modelPatient->middle_name); ?></h4>
	<div class="row">
		<div class="col-xs-10 b-paid__bodyLeft">
			<h4 class="b-paid__serviceHeader">Услуги</h4>
		</div>
		<div class="col-xs-2">
			<?php $this->widget('PaidManageButtons'); ?>
		</div>
	</div>
</div>