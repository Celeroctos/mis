<?php
/**
 * Шаблон для печати счёта
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Печать счёта';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $this->pageTitle; ?></title>
		<?php Yii::app()->clientScript->registerPackage('print'); ?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					МОНИИАГ
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-xs-offset-3">
					<h3>Счёт № <?= $recordPaid_Expense->expense_number; ?></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					№ карты
				</div>
				<div class="col-xs-3">
					<?= $recordPaid_Medcard->paid_medcard_number; ?>
				</div>
				<div class="col-xs-5">
					Платные услуги
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					Пациент
				</div>
				<div class="col-xs-10">
					<?= $recordPatient->last_name;?> <?= $recordPatient->first_name; ?> <?= $recordPatient->middle_name;?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">
					Возраст
				</div>
				<div class="col-xs-3">
					<?= Patients::getAge($recordPatient->birthday); ?>
				</div>
				<div class="col-xs-5">
					Дата приема:
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<?php
					$this->widget('zii.widgets.grid.CGridView', [
						'dataProvider'=>$modelOrder_Details->search($paid_order_id),
						'filter'=>$modelOrder_Details,
						'ajaxType'=>'post',
						'id'=>'gridPrintExpense',
	//					'id'=>$modelDoctors->hash, //сохраняем ID при обновлении ajax
						'ajaxVar'=>'gridPrintExpense',
						'template'=>'{pager}{items}',
						'ajaxUpdate'=>true,
						'enableSorting'=>false,
						'emptyText'=>
						'<h4 class="b-paid__emptyServiceHeader">Нет услуг.</h4>',
						'showTableOnEmpty'=>false,
						'itemsCssClass'=>'table table-bordered gridPrintExpense', //gridSelectServices используется в paid.js
						'pager'=>[
							'class'=>'CLinkPager',
							'cssFile'=>'',
							'selectedPageCssClass'=>'active',
							'firstPageCssClass'=>'',
							'hiddenPageCssClass'=>'',
							'internalPageCssClass'=>'',
							'nextPageLabel'=>false,
							'prevPageLabel'=>false,
							'lastPageCssClass'=>'',
							'nextPageCssClass'=>'',
							'maxButtonCount'=>'7',
							'previousPageCssClass'=>'',
							'selectedPageCssClass'=>'active',
							'header'=>false,
							'htmlOptions'=>[
								'class'=>'pagination pagination-sm b-paid__selectServicePagination',
							]
						],
						'columns'=>[
							[
								'name'=>'service.code',
							],
							[
								'name'=>'service.name'
							],
							[
								'name'=>'price',
								'type'=>'raw',
								'value'=>'ParseMoney::decodeMoney($data->service->price) . " руб."',
							],
							[
								'name'=>'doctor.last_name',
								'value'=>'$data->doctor->last_name  . " " . $data->doctor->first_name . " " . $data->doctor->middle_name',
							]
						],
					]);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-8">
					Кто направил на исслед.<br>
					______________________<br>
					
					Регистратор dbo<br>
					Код МКБ__________ &nbsp&nbsp&nbsp&nbsp Отделение
					
				</div>
				<div class="col-xs-4">
					ИТОГО: <?= ParseMoney::decodeMoney($recordPaid_Expense->price) . ' руб.' ?>
				</div>
			</div>
		</div>
	</body>
</html>