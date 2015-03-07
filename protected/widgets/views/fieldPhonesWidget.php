<?php
/**
 * Шаблон виджета. Вставляется внутрь формы.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="b-phones">
	<?= $form->Label($model, 'value', ['class'=>'control-label']); ?>
	<span class="b-phones__spanPlus glyphicon glyphicon-plus" id="b-phones__add" aria-hidden="true"></span>
	<div class="b-phones__input">
		<input style="opacity: 1;" class="form-control input-sm" name="Patient_Contacts[value][0]">
	</div>
</div>