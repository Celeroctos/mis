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
			//TODO ограничить макс. кол-во символов в названии групп, иначе будет съезжать
			['name', 'required', 'on'=>'paid.cash.create'],
			['code', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['p_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
		];
	}
	
	/**
	 * Рекурсивный метод для вывода услуг и их групп.
	 * Программно вложенность неограничена.
	 * Генерирует HTML-код из-за сложности организации его в представлении.
	 * @param object $record
	 * @param int $level Уровень вложенности группы в группу
	 */
	public static function recursServicesOut($record, $level=0)
	{
		if(empty($record))
		{
			?>
			<div class="row">
				<div class="col-xs-12">
					<h4 class="b-paid__emptyServiceGroupHeader">Не найдено ни одной группы!</h4>
				</div>
			</div>
			<?php
		}
		?><ul><?php
		foreach($record as $value) //просмотр групп
		{
			$modelPaid_Services=new Paid_Services(); //передача в CGridView
			$modelPaid_Services->paid_service_group_id=$value->paid_service_group_id;
			?>
			<li>
				<div class="b-paid__serviceItemGroup">
				<?= CHtml::encode($value->name); ?>
				<span class="glyphicon glyphicon-plus b-paid__addSubGroup" id="<?= CHtml::encode($value->paid_service_group_id); ?>" tabindex="-1" data-contect="" aria-hidden="true"></span>
				<span class="glyphicon glyphicon-pencil b-paid__servicesGroupPencil" tabindex="-1" aria-hidden="true"></span>
				</div>
			</li>
			<?php
			$recordChild=Paid_Service_Groups::model()->findAll('p_id=:p_id', [':p_id'=>$value->paid_service_group_id]); //ищем всех предков
			//проверяем является ли он чьим-то child
			if(!empty($recordChild))
			{ //есть дочерние элементы.
				$level+=1; //кол-во пикселей для вывода
				Paid_Service_Groups::recursServicesOut($recordChild, $level);
				$level-=1;
			}
		}
		?></ul><?php
	}
	public function tableName()
	{
		return 'paid.paid_service_groups';
	}
}