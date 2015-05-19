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
		</div>
	</body>
</html>