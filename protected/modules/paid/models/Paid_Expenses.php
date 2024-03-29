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
	public $hashForm; //use in Form id
	public $dateEnd; //in CGridView search
	public $patient_id; //use in compare
	public $max_number; //aggregate
	
	/**
	 * Выбор счетов в зависимости от действия (оплаченные, неоплаченные и т.д.)
	 * Не связано с хранилищем, принимает константу как значение.
	 * @var string
	 */
	public $action=self::NOT_PAID;
	
	/**
	 * Начальное число для генератора номера счёта. Задаётся при внедрении к заказчику.
	 */
	const START_SEQUENCE = 1;
	
	const NOT_PAID = 0; //не оплачен
	const PAID = 1; //оплачен
	const RETURN_PAID = 2; //возврат оплаты
	
	const NOT_PAID_NAME = 'Не оплачен';
	const PAID_NAME = 'Оплачен';
	const RETURN_PAID_NAME = 'Возвращён';
	
	const PAGE_SIZE = 12;
	
	public function tableName()
	{
		return 'paid.paid_expenses';
	}

	/**
	 * Генератор
	 * @param integer $start Начальное число. С него начинается последовательность.
	 */
	public static function expenseSequenceNumber($start=Paid_Expenses::START_SEQUENCE)
	{
		$criteria=new CDbCriteria;
		$criteria->select='max("t"."expense_number") as "max_number"';
		$recordExpense=Paid_Expenses::model()->find($criteria);
		
		if($recordExpense===null)
		{
			return $start;
		}
		else
		{
			return ++$recordExpense->max_number;
		}	
	}
	
	public function attributeLabels()
	{
		return [
			'paid_expense_id'=>'ID',
			'date'=>'Дата',
			'dateEnd'=>'Дата конца',
			'price'=>'Цена',
			'paid_order_id'=>'Заказ',
			'expense_number'=>'Номер счёта',
			'patientName'=>'Пациент', //use in GridView Journal
			'services'=>'Услуги', //use in GridView Journal
			'Doctors'=>'Врачи',
		];
	}

	public function rules()
	{
		return [
			['hash', 'type', 'type'=>'string'],
			['date, dateEnd', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cashAct.search'],
			['date, dateEnd', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.journal.all'],
			['date, dateEnd', 'date', 'format'=>'yyyy-MM-dd', 'on'=>'paid.cashAct.returnPayment.search'],
			['paid_order_id', 'unique', 'on'=>'paid.cashAct.create'],
			['expense_number', 'unique', 'on'=>'paid.cashAct.create'],
		];
	}
	
	public function relations()
	{
		return [
			'order'=>[self::BELONGS_TO, 'Paid_Orders', 'paid_order_id'],
		];
	}
	
	/**
	 * Вернуть статус счёта в GridView
	 */
	public function getStatus($status)
	{
		switch($status)
		{
			case self::NOT_PAID:
				return self::NOT_PAID_NAME;
			case self::PAID:
				return self::PAID_NAME;
			case self::RETURN_PAID:
				return self::RETURN_PAID_NAME;
		}
	}
	
	/**
	 * Метод, возвращающий всех докторов по счёту.
	 * @param integer $paid_expense_id Номер счёта
	 */
	public function getDoctors($paid_expense_id)
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
		
		$recordPaid_Order_Details=Paid_Order_Details::model()->findAll('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordPaid_Order->paid_order_id]);
		
		$count=count($recordPaid_Order_Details);
		
		if($count===0)
		{
			return 'Врачи отсутствуют.';
		}
	
		$i=1;
		foreach($recordPaid_Order_Details as $value)
		{
			$recordDoctors=Doctors::model()->findByPk($value->doctor_id);
			
			if($recordDoctors!==null)
			{
				echo $recordDoctors->last_name . ' ' . $recordDoctors->first_name . ' ' . $recordDoctors->middle_name;
				
				if($i!=$count) // отсекаем последнюю запятую
				{
					echo ', ';
				}
				$i++;
			}
		}
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
		
		if($count===0)
		{
			return 'Услуги отсутствуют.';
		}
		
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
	{ /* search for actionChooseExpenses */
		$criteria=new CDbCriteria;
		$criteria->addCondition('status='. $this->action);
		$criteria->with=['order'=>['joinType'=>'INNER JOIN']];
		$criteria->together=true;
		
		if(isset($this->date) && isset($this->dateEnd) && strlen($this->date)>0 && strlen($this->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $this->date, $this->dateEnd);
		}
		$criteria->compare('"order"."patient_id"', $this->patient_id);
		$criteria->compare('cast(expense_number as varchar)', $this->expense_number, true);
		
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
