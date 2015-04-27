<?php
/**
 * Основной шаблон модуля платных услуг
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<title><?= CHtml::encode($this->pageTitle); ?></title>
	<?php Yii::app()->clientScript->registerPackage('paid'); ?>
	<?php Yii::app()->clientScript->registerPackage('fieldPhonesWidget'); ?>
	<?php Yii::app()->clientScript->registerPackage('fieldDocumentsWidget'); ?>
	<?php Yii::app()->clientScript->registerPackage('jquery-ui'); ?>
	<?php Yii::app()->clientScript->registerPackage('jquery.inputmask'); ?>
</head>
<body>
	<?php $this->widget('HeaderWidget'); ?>
	<?php $this->widget('FlashMessager'); ?>
	<div class="container b-paid">
		<?php $this->widget('PaidNavWidget'); ?>
		<div class="b-content">
			<?= $content; ?>
		</div>
	</div>
	<?php $this->widget('FooterWidget'); ?>
</body>
</html>