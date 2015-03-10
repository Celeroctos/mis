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
	<?= $form->TextField($model, 'value', ['id'=>'Patient_Contacts_value', 'name'=>'Patient_Contacts[value][0]', 'class'=>'form-control input-sm']); ?>
	</div>
	<?= $form->error($model, 'value', ['class'=>'b-paid__errorFormPatient']); ?>
</div>