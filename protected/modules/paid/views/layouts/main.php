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
	<?php Yii::app()->clientScript->registerPackage('paid') ?>
	<title><?= CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php $this->widget('HeaderWidget'); ?>
	<div class="container">
		<div class="b-paidHeader">
			<div class="row">
				<div class="col-xs-12">
					<div class="b-paidNav">
						<ul class="nav nav-pills nav-justified">
								<li role="presentation" class="active"><a class="b-paidNav__href" href="#">Касса</a></li>
								<li role="presentation"><a class="b-paidNav__href" href="#">Журнал</a></li>
								<li role="presentation"><a class="b-paidNav__href" href="#">Отчёты</a></li>
								<li role="presentation"><a class="b-paidNav__href" href="#">Прайс-лист</a></li>
								<li role="presentation"><a class="b-paidNav__href" href="#">Настройки</a></li>
								<li role="presentation"><a class="b-paidNav__href" href="#">Пациенты</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php $this->widget('FlashMessager'); ?>
		<div class="b-paidBody">
			<div class="row">
				<div class="col-md-12">
						<?= $content; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>