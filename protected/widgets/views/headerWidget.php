<?php
/**
 * Шапка
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="container b-header">
	<div class="row">
		<div class="col-xs-12">
			МИС Notum
			<?php if(!Yii::app()->user->isGuest): ?>
				<?= CHtml::link('Выйти', Yii::app()->createUrl('service/auth/logout'), ['class'=>'btn btn-default btn-sm']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>