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
		
		<h4 class="b-paid__selectHeader">Выбранные услуги</h4>
		<div id="selectedServicesTable">
			<table class="table table-bordered">
				<thead>
					<th>
						Код услуги
					</th>
					<th>
						Название
					</th>
					<th>
						Название группы
					</th>
					<th>
						Цена
					</th>
					<th>
						Действует с
					</th>
					<th>
						Действует до
					</th>
				</thead>
				<tbody>
					<tr class="empty">
						<td colspan="7"><span>Выберите услуги</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php $this->widget('PaidActWidget'); ?>
</div>
<?php $this->widget('PaidCashPunch'); ?>
<div class="modal" id="modalSelectServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectServices">
		<div class="modal-content b-paid__modalHeader">
			<div class="modal-body" id="modalSelectServicesBody">
			</div>
			<div class="modal-footer">
				<button type="button" id="selectedServicesConfirm" class="btn btn-primary" data-dismiss="modal">Выбрать</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>