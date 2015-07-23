<?php
/**
 * Шаблон пациента
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Пациент';
?>
<div class="row b-paid_position">
	<div class="col-xs-10">
		<?php $this->widget('zii.widgets.CDetailView', [
			'data'=>$modelPatient,
			'nullDisplay'=>'<font color="red">Значение не заполнено</font>',
			'attributes'=>[
				[
					'name'=>'last_name',
					'htmlOptions'=>'col-xs-1',
				],
				'first_name',        // an attribute of the related object "owner"
				'middle_name',
				'address_reg_str',
				'address_str',
				'birthday',
				'paid_medcards.paid_medcard_number',
			],
		]); ?>
<?= CHtml::button('Редактировать пациента', ['class'=>'btn btn-primary btn-sm', 'id'=>'updatePatient']); ?>
		
		<h4 class="b-paid__selectHeader">Услуги, включенные в заказ</h4>
		<div id="selectedServicesTable">
			<table class="table table-bordered">
				<thead>
					<th>
						Код услуги
					</th>
					<th>
						Название услуги
					</th>
					<th>
						Название группы
					</th>
					<th>
						Цена
					</th>
					<th>
						Врач
					</th>
<!--				<th>
						Действует с
					</th>
					<th>
						Действует до
					</th>-->
				</thead>
				<tbody>
					<tr class="empty">
						<td colspan="7"><span>Пусто</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php $this->widget('PaidActWidget', ['modelPatient'=>$modelPatient]); ?>
</div>
<?php $this->widget('PaidCashPunch'); ?>
<!--<div class="modal" id="modalSelectServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectServices">
		<div class="modal-content b-modalContentSelectServices b-paid__modalHeader">
			<div class="modal-body" id="modalSelectServicesBody">
			</div>
			<div class="modal-footer">
				<button type="button" id="selectedServicesConfirm" class="btn btn-primary" data-dismiss="modal">Сформировать заказ</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalSelectDoctor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectDoctor">
		<div class="modal-content b-paid__modalHeader">
			<div class="modal-body" id="modalSelectDoctorBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>-->
<div class="modal" id="modalSelectExpenses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectExpenses">
		<div class="modal-content b-modalSelectExpenseServices__content b-paid__modalHeader">
			<div class="modalOverlay">
				<div class="modal-body" id="modalSelectExpensesBody">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalSelectExpenseServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalSelectExpenseServices">
		<div class="modal-content b-paid__modalHeader">
			<div class="modal-body" id="modalSelectExpenseServicesBody">
			</div>
			<div class="modal-footer">
				<?= CHtml::button('Выбрать', ['class'=>'btn btn-primary', 'id'=>'confirmExpenseOrder']); ?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalReturnPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalReturnPayment">
		<div class="modal-content b-modalReturnPayment b-paid__modalHeader">
			<div class="modalOverlayReturnPayment">
				<div class="modal-body" id="modalReturnPaymentBody">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalUpdatePatient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content b-modalUpdatePatient b-paid__modalHeader">
			<div class="modal-body" id="modalUpdatePatientBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modalReturnPaymentConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog b-modalReturnPaymentConfirm">
		<div class="modal-content b-modalReturnPaymentConfirm b-paid__modalHeader">
			<div class="modalOverlayReturnPaymentConfirm">
				<div class="modal-body" id="modalReturnPaymentConfirmBody">
					<div class="b-paid__returnPrice">
						Сумма возврата: <span class="price"></span>
					</div>
					<div>
						<?= CHtml::button('Выполнить возврат', ['class'=>'btn btn-primary', 'id'=>'returnPaymentConfirm']); ?>
						<?= CHtml::button('Отмена', ['class'=>'btn btn-default', 'data-dismiss'=>'modal']); ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</div>