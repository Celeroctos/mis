<?php
/**
 * Счета
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class Paid_Expenses extends ActiveRecord
{
	public $paid_expense_id;
	public $date;
	public $price;
	public $paid_order_id; //ONE TO ONE
	public $expense_number;
	public $status;
	public $user_create_id;
	
	public $patientName; //user in CGridView (gridChooseExpenses.php)
	public $services; //услуги, включенные в счёт (gridChooseExpenses.php)
	public $hash; //use in CGridView id
	
	const NOT_PAID = 0;
	const PAID = 1;
	
	const PAGE_SIZE = 7;
	
	public function tableName()
	{
		return 'paid.paid_expenses';
	}
	
	public function rules()
	{
		return [
			['hash', 'type', 'type'=>'string'],
			['paid_order_id', 'unique'],
			['expense_number', 'unique'],
		];
	}
	
	public function relations()
	{
		return [
			'order'=>[self::BELONGS_TO, 'Paid_Orders', 'paid_order_id'],
		];
	}
	
	/**
	 * Метод, возвращающий все услуги по счёту.
	 * @param integer $paid_expense_id Номер счёта
	 */
	public function getServices($paid_expense_id)
	{
		$recordPaid_Expense=Paid_Expenses::model()->findByPk($paid_expense_id);
		if($recordPaid_Expense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		$recordPaid_Order=Paid_Orders::model()->findByPk($recordPaid_Expense->paid_order_id);
		if($recordPaid_Order===null)
		{
			throw new CHttpException(404, 'Такого заказа не существует.');
		}
		 
		//ловим сами услуги из заказа, который привязан к данному счёту
		$recordPaid_Order_Details=Paid_Order_Details::model()->findAll('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordPaid_Order->paid_order_id]);
		$count=count($recordPaid_Order_Details); //сколько всего нашлось услуг
		$i=1;
		foreach($recordPaid_Order_Details as $value)
		{
			$recordPaid_Services=Paid_Services::model()->findByPk($value->paid_service_id);
			if($recordPaid_Services!==null)
			{
				echo $recordPaid_Services->name;
				if($i!=$count) //отсекаем последнюю запятую
				{
					echo ', ';
				}
				$i++;
			}
		}
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('status='. self::NOT_PAID);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('expense_number', $this->expense_number, true);
		
		return new CActiveDataProvider('Paid_Expenses', [
			'criteria'=>$criteria,
			'sort'=>[
				'defaultOrder'=>[
					'paid_expense_id'=>CSort::SORT_DESC,
				],
			],
			'pagination'=>[
				'pageSize'=>self::PAGE_SIZE,
			],
		]);		
	}
}
