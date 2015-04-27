<?php
/**
 * Шаблон
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="row b-paidNav">
	<nav>
		<ul class="nav nav-pills nav-justified">
			<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="<?= Yii::app()->controller->createUrl('cash/main'); ?>">Касса</a></li>
			<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href="<?= Yii::app()->controller->createUrl('journal/index'); ?>">Журнал</a></li>
			<li role="presentation" class="b-paidNav__li"><a class="b-paidNav__href" href=<?= Yii::app()->controller->createUrl('cash/groups'); ?>>Услуги</a></li>
		</ul>
	</nav>
</div>
