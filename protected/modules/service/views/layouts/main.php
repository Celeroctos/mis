<?php
/**
 * Основной шаблон модуля service
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<?php Yii::app()->clientScript->registerPackage('service') ?>
	<title><?= CHtml::encode($this->pageTitle); ?></title>
</head>
<body class="login">
	<div class="tableCell">
		<?= $content; ?>
	</div>
<!--	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php $this->widget('FlashMessager'); ?>
				<?= $content; ?>
			</div>
		</div>
	</div>-->
</body>
</html>