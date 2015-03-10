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
	<?php Yii::app()->clientScript->registerPackage('paid'); ?>
	<?php Yii::app()->clientScript->registerPackage('fieldPhonesWidget'); ?>
	<?php Yii::app()->clientScript->registerPackage('fieldDocumentsWidget'); ?>
	<title><?= CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php $this->widget('HeaderWidget'); ?>
	<?php $this->widget('FlashMessager'); ?>
	<?= $content; ?>
	<?php $this->widget('FooterWidget'); ?>
</body>
</html>