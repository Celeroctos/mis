<?php
/**
 * AR модель для работы с группами услуг платного модуля.
 * У каждой услуги есть своя группа, вложенность неограничена.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Service_Groups extends ActiveRecord
{
	public $paid_service_group_id;					
	public $name;
	public $code;
	public $p_id;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return [
			['name', 'required', 'on'=>'paid.cash.create'],
			['code', 'type', 'type'=>'string', 'on'=>'paid.cash.create']
		];
	}
	
	/**
	 * Рекурсивный метод для вывода услуг и их групп.
	 * Программно вложенность неограничена.
	 * @param object $record
	 * @param int $level Уровень вложенности группы в группу
	 */
	public static function recursServicesOut($record, $level)
	{
		if(empty($record))
		{
			?>
			<div class="row">
				<div class="col-xs-12">
					<h4 class="b-paid__emptyServiceGroupHeader">Не найдено ни одной группы!</h4>
					<?= CHtml::htmlButton('Добавить группу', ['class'=>'btn btn-block btn-primary b-paid__buttonServiceGroupAdd', 'id'=>'paid_cash_servicesList-buttonEmptyGroups']); ?>
				</div>
			</div>
			<?php
		}
		foreach($record as $value) //просмотр групп
		{
			$modelPaid_Services=new Paid_Services(); //передача в CGridView
			$modelPaid_Services->paid_service_group_id=$value->paid_service_group_id;
			?>
			<div class="row">
				<div class="col-xs-3">
					<div class=" b-paid__serviceItemGroup">
						<?= CHtml::encode($value->name); ?>
					</div>
				</div>
				<div class="col-xs-9">
					<?php Yii::app()->controller->renderPartial('servicesListGrid', ['model'=>$modelPaid_Services]); ?>
				</div>
			</div>
			<?php
			$recordChild=Paid_Service_Groups::model()->findAll('p_id=:p_id', [':p_id'=>$value->paid_service_group_id]);
			//проверяем является ли он чьим-то child
			if(!empty($recordChild))
			{ //есть дочерние элементы.
				$level++;
				Paid_Service_Groups::recursServicesOut($recordChild, $level);
				$level--;
			}
		}
	}
	public function tableName()
	{
		return 'paid.paid_service_groups';
	}
}