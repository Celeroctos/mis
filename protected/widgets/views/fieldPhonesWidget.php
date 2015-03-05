<?php
/**
 * Шаблон виджета. Вставляется внутрь формы.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="ajax_f">
	<a id="add_elem" href="#">Добавить элемент</a>
	<?= $form->Label($model, 'phone_number', ['class'=>'control-label']); ?>
	<?=	CHtml::TextField('Patients', null, ['class'=>'form-control input-sm']); ?>
</div>