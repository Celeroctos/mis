<?php
/**
 * Шаблон кассы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle="Касса";
?>
<div class="row">
	<div class="col-xs-12">
		<div class="form-inline">
			<?php $form=$this->beginWidget('CActiveForm'); ?>
					<?= $form->errorSummary($model, '', '', [
						'class'=>'alert alert-warning',
					]); ?>
				<div class="form-group">
						<?= $form->Label($model, 'first_name', ['class'=>'control-label']); ?>
						<?= $form->TextField($model, 'first_name', [
										'class'=>'form-control',
									]); ?>
				</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>