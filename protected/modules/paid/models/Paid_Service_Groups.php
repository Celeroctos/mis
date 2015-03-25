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

	public function attributeLabels()
	{
		return [
			'paid_service_group_id'=>'#ID',
			'name'=>'Название',
			'code'=>'Код группы',
			'p_id'=>'Группа',
		];
	}	
	
	public function rules()
	{
		return [
			//TODO ограничить макс. кол-во символов в названии групп, иначе будет съезжать
			['name', 'required', 'on'=>'paid.cash.create'],
			['code', 'type', 'type'=>'string', 'on'=>'paid.cash.create'],
			['p_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.create'],
			
			['name', 'required', 'on'=>'paid.cash.update'],
			['code', 'type', 'type'=>'string', 'on'=>'paid.cash.update'],
			['p_id', 'type', 'type'=>'integer', 'on'=>'paid.cash.update'], //здесь изменяет сам пользователь
		];
	}
	
	public static function getServiceGroupsListData($group_id=null)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('paid_service_group_id!=:group_id');
		$criteria->params=[':group_id'=>$group_id]; //нельзя изменить группу так, чтобы родитель являлся самим собой.
		$serviceGroupsList=  Paid_Service_Groups::model()->findAll($criteria);
		return CHtml::listData(
				CMap::mergeArray([
							[
								'paid_service_group_id'=>0,
								'name'=>'Главная группа',
							]
				], $serviceGroupsList),
				'paid_service_group_id',
				'name'
		);
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
					<h4 class="b-paid__emptyServiceGroupHeader">Нет групп</h4>
				</div>
			</div>
			<?php
		}
		?><ul class="b-paid_UL"><?php
		foreach($record as $value) //просмотр групп
		{
			$modelPaid_Services=new Paid_Services(); //передача в CGridView
			$modelPaid_Services->paid_service_group_id=$value->paid_service_group_id;
			?>
			<li>
				<div class="b-paid__serviceItemGroup">
					<?= CHtml::link(CHtml::encode($value->name), ['cash/serviceGroupsList', 'group_id'=>$value->paid_service_group_id], ['class'=>'b-paid__serviceItemGroupLink']) ?>
					<span class="glyphicon glyphicon-plus b-paid__addPopover" value="<?= CHtml::encode($value->paid_service_group_id); ?>" tabindex="-1" data-contect="" aria-hidden="true"></span>
				<span class="glyphicon glyphicon-pencil b-paid__addEditPopover" value="<?= CHtml::encode($value->paid_service_group_id); ?>" tabindex="-1" aria-hidden="true"></span>
				</div>
			</li>
			<?php
			$recordChild=Paid_Service_Groups::model()->findAll('p_id=:p_id ORDER BY paid_service_group_id DESC', [':p_id'=>$value->paid_service_group_id]); //ищем всех предков
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