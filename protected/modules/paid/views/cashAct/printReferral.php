<?php
/**
 * Шаблон для печати направления
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
$this->pageTitle='Печать направления';
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
				<div class="col-xs-8 col-xs-offset-2">
					<h3>Направление № <?= $recordReferral->referral_number; ?></h3>
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
						'dataProvider'=>$modelReferrals_Details->search($paid_referral_id),
//						'filter'=>$modelReferrals_Details,
						'ajaxType'=>'post',
						'id'=>'gridPrintReferral',
	//					'id'=>$modelDoctors->hash, //сохраняем ID при обновлении ajax
						'ajaxVar'=>'gridPrintReferral',
						'template'=>'{pager}{items}',
						'ajaxUpdate'=>true,
						'enableSorting'=>false,
						'emptyText'=>
						'<h4 class="b-paid__emptyServiceHeader">Нет услуг.</h4>',
						'showTableOnEmpty'=>false,
						'itemsCssClass'=>'table table-bordered gridPrintReferral', //gridSelectServices используется в paid.js
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
								'value'=>'ParseMoney::decodeMoney($data->price) . " руб."',
							],
//							[
//								'name'=>'doctor',
//								'value'=>'$data->referral->doctor->last_name  . " " . $data->referral->doctor->first_name . " " . $data->referral->doctor->middle_name',
//							]
						],
					]);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-5">
					Кто направил на исслед.<br>
					______________________<br>
					
					Регистратор <?= Yii::app()->user->name; ?><br>
					Код МКБ__________________  Отделение
				</div>
				<div class="col-xs-5">
					<br><br>
					Врач: <?= $recordDoctor->last_name . ' ' . $recordDoctor->first_name . ' ' . $recordDoctor->middle_name; ?>
				</div>
			</div>
		</div>
	</body>
</html>