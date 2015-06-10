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
		<div class="row">
			<div class="col-xs-12 b-content">
				<?= $content; ?>
			</div>
		</div>
	</div>
	<?php $this->widget('FooterWidget'); ?>
	<div class="modal" id="modalSelectJournalRow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog b-modalSelectJournalRow">
			<div class="modal-content">
				<div class="modal-body" id="modalSelectJournalRowBody">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>