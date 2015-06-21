<?php
/**
 * Основной шаблон печати
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<title><?= CHtml::encode($this->pageTitle); ?></title>
	<?php Yii::app()->clientScript->registerPackage('print'); ?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?= $content; ?>
			</div>
		</div>
	</div>
</body>
</html>