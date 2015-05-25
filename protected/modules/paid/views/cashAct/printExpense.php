<?php
/**
 * Шаблон для печати счёта
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Печать счёта';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $this->pageTitle; ?></title>
		<?php Yii::app()->clientScript->registerPackage('print'); ?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					МОНИИАГ
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-xs-offset-3">
					<h3>Счёт № <?= $recordPaid_Expense->expense_number; ?></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					№ карты
				</div>
				<div class="col-xs-10">
					<?= $recordPaid_Medcard->paid_medcard_number; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					Пациент
				</div>
				<div class="col-xs-10">
					<?= $recordPatient->last_name;?> <?= $recordPatient->first_name; ?> <?= $recordPatient->middle_name;?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					Возраст
				</div>
				<div class="col-xs-3">
					<?= Patients::getAge($recordPatient->birthday); ?>
				</div>
				<div class="col-xs-5">
					Дата приема:
				</div>
			</div>
			<div class="row">
				 тут CGridView !
			</div>
		</div>
	</body>
</html>