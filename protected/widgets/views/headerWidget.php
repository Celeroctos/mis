<?php
/**
 * Шапка
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="b-header">
	<div class="container">
		<div class="b-header__body">
			<div class="row">
				<div class="col-xs-2">
					МИС Notum
					<?php if(!Yii::app()->user->isGuest): ?>
						<?= CHtml::link('Выйти', Yii::app()->createUrl('service/auth/logout'), ['class'=>'btn btn-default btn-block btn-sm']); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>