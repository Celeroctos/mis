<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<div class="row b-paid_position">
	<div class="col-xs-10">
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
	<?php $this->widget('PaidActWidget'); ?>
</div>
<?php $this->widget('PaidCashPunch'); ?>