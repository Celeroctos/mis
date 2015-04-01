<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="row">
	<div class="col-xs-8">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$modelPatient,
			'nullDisplay'=>'Пациент не выбран!',
			'attributes'=>[
				[
					'name'=>'last_name',
					'htmlOptions'=>'col-xs-1',
				],
				'first_name',        // an attribute of the related object "owner"
				'middle_name',
				'address_reg',
			],
		)); ?>
	</div>
</div>