<?php
/**
 * Шаблон
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="container b-paidNav">
	<div class="row">
		<nav>
			<ul class="nav nav-pills nav-justified">
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="<?= Yii::app()->controller->createUrl('cash/index'); ?>">Касса</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Журнал</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Отчёты</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href=<?= Yii::app()->controller->createUrl('cash/servicesList'); ?>>Услуги</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Настройки</a></li>
				<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="#">Пациенты</a></li>
			</ul>
		</nav>
	</div>
</div>
