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
			<?= $form->DropDownList($model, 'type', $documentTypeListData, [
							'class'=>'form-control input-sm',
							'name'=>'Patient_Documents[type][0]',
							'id'=>'Patient_Documents_type',
						]); ?>
			<?= $form->error($model, 'type', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-3">
			<?= $form->Label($model, 'serie', ['class'=>'control-label']); ?>
			<?= $form->TextField($model, 'serie', [
							'class'=>'form-control input-sm',
							'name'=>'Patient_Documents[serie][0]',
							'id'=>'Patient_Documents_serie',
						]); ?>
			<?= $form->error($model, 'serie', ['class'=>'b-paid__errorFormPatient']); ?>
		</div>
		<div class="col-xs-3">
			<?= $form->Label($model, 'number', ['class'=>'control-label']); ?>
			<?= $form->TextField($model, 'number', [
							'class'=>'form-control input-sm',
							'name'=>'Patient_Documents[number][0]',
							'id'=>'Patient_Documents_number',
						]); ?>
			<?= $form->error($model, 'number', ['class'=>'b-paid__errorFormPatient']); ?>				
		</div>
		<div class="col-xs-3">
			<span class="b-documents__spanPlus glyphicon glyphicon-plus" id="b-documents__add" aria-hidden="true"></span>
		</div>
	</div>
</div>