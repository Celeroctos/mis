<?php
/**
 * Шаблон для динамики input
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="b-documents">
	<div class="row">
		<div class="col-xs-3">
			<?= $form->Label($model, 'type', ['class'=>'control-label']); ?>
			<?= $form->DropDownList($model, 'type', Patients::getDocumentTypeListData(), [
							'class'=>'form-control input-sm',
						]); ?>
			<?= $form->error($model, 'type', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-3">
			<?= $form->Label($model, 'serie', ['class'=>'control-label']); ?>
			<?= $form->TextField($model, 'serie', [
							'class'=>'form-control input-sm',
						]); ?>
			<?= $form->error($model, 'serie', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-3">
			<?= $form->Label($model, 'number', ['class'=>'control-label']); ?>
			<?= $form->TextField($model, 'number', [
							'class'=>'form-control input-sm',
						]); ?>
			<?= $form->error($model, 'number', ['class'=>'b-paid__errorFormPatient']); ?>				
		</div>
<!--		<div class="col-xs-3"> Расскомментировать для возможности добавления более 1. (в этом случае нужны фиксы по JS)
			<span class="b-documents__spanPlus glyphicon glyphicon-plus" id="b-documents__add" aria-hidden="true"></span>
		</div>-->
	</div>
</div>