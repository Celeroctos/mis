<?php
/**
 * Контроллер действий модуля услуг (кнопки справа)
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class CashActController extends MPaidController
{
	public function accessRules()
	{
		return [
			[
				'allow', //разрешить только авториз. юзерам.
				'controllers'=>['paid/cashAct'],
				'users'=>['@'],
			],
			[
				'deny', //запрет всем остальным и перенаправление.
				'deniedCallback'=>[$this, 'redirectToDenied'],
				'controllers'=>['paid/cashAct'],
			],
		];
	}
	
	/**
	 * Отключаем уже подключенные скрипты
	 */
	public static function disableScripts()
	{
		Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
//		Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.js']=false;
	}
	
	/**
	 * Выбор услуг (CGridView) в модальном окне
	 * Грузится из экшна /paid/cash/actionpatient() ajax-запросом.
	 * Результат грузится в модальное окно.
	 */
	public function actionSelectServices()
	{
		self::disableScripts();
		$modelPaid_Service=new Paid_Services('paid.cashAct.select');
		$modelPaid_Service->modelPaid_Service_Groups=new Paid_Service_Groups('paid.cashAct.select'); //for search in CGridView
		$modelDoctors=new Doctors();
		
		$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
		$modelPaid_Service->modelPaid_Service_Groups->attributes=Yii::app()->request->getPost('Paid_Service_Groups'); //for search in CGridView
		
		if(!Yii::app()->request->getParam('gridSelectServices'))
		{
			$modelPaid_Service->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$this->renderPartial('gridSelectServices', ['modelPaid_Service'=>$modelPaid_Service, 'modelDoctors'=>$modelDoctors], false, true);
	}
	
	/**
	 * Возврат платежа
	 */
	public function actionReturnPayment($patient_id)
	{
		self::disableScripts();
		
		$modelPaid_Expenses=new Paid_Expenses('paid.cashAct.returnPayment.search');
		$modelPatient=new Patients('paid.cashAct.returnPayment.search');
		
		$modelPaid_Expenses->patient_id=$patient_id;
		$modelPaid_Expenses->action=Paid_Expenses::PAID; //выбираем только оплаченные счета
		$modelPaid_Expenses->hashForm=substr(md5(uniqid("", true)), 0, 4);
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		
		/**
		 * перенести в search() модели. (Повторы кода, некритично)...
		 */
		$criteria=new CDbCriteria;
		$criteria->with=['order.patient'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->addCondition('status='. $modelPaid_Expenses->action);
		$criteria->addCondition('patient.patient_id=:patient_id');
		$criteria->params=[':patient_id'=>$patient_id];
		/**
		 * Не осуществляется. На будущее.
		 */
		$criteria->compare('LOWER(patient.last_name)', mb_strtolower($modelPatient->last_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.first_name)', mb_strtolower($modelPatient->first_name, 'UTF-8'));
		$criteria->compare('LOWER(patient.middle_name)', mb_strtolower($modelPatient->middle_name, 'UTF-8'));
		
		/**
		 * перенести в ->search() модели.
		 */
		if(isset($modelPaid_Expenses->date) && isset($modelPaid_Expenses->dateEnd) && strlen($modelPaid_Expenses->date)>0 && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$criteria->addBetweenCondition('date', $modelPaid_Expenses->date, $modelPaid_Expenses->dateEnd.' 23:59:59');
		}
	
		$dataProvider=new CActiveDataProvider($modelPaid_Expenses, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Expenses::PAGE_SIZE,], 'sort'=>['defaultOrder'=>['paid_expense_id'=>CSort::SORT_DESC]]]);
		
		if(!Yii::app()->request->getParam('gridReturnPayment'))
		{ // первый вход
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$this->renderPartial('gridReturnPayment', ['dataProvider'=>$dataProvider, 'modelPaid_Expenses'=>$modelPaid_Expenses, 'modelPatient'=>$modelPatient], false, true);
	}
	
	/**
	 * Выбранный платёж на возврат
	 * @param string $expense_number Номер счета
	 */
	public function actionReturnPaymentConfirm($expense_number)
	{
		self::disableScripts();
		
		$modelPaid_Expense=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		
		if($modelPaid_Expense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		$modelPaid_Payment=Paid_Payments::model()->find('paid_expense_id=:paid_expense_id', [':paid_expense_id'=>$modelPaid_Expense->paid_expense_id]);
		
		if($modelPaid_Payment===null)
		{
			throw new CHttpException(404, 'Такого платежа не существует.');
		}
		$transaction=Yii::app()->db->beginTransaction();
		
		try
		{
			$modelPaid_Expense->status=Paid_Expenses::RETURN_PAID;
			
			if(!$modelPaid_Expense->save())
			{
				throw new CHttpException(404, 'Не удалось обновить статус у счёта. Транзакция возврата отменена.');
			}

			$modelPaid_Payment->date_delete=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
			$modelPaid_Payment->reason_delete=Paid_Payments::RETURN_REASON_DELETE;
			$modelPaid_Payment->user_delete_id=Yii::app()->user->id;
			
			if(!$modelPaid_Payment->save())
			{
				throw new CHttpException(404, 'Не удалось обновить статус платежа. Транзакция возврата отменена.');
			}
			
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	/**
	 * Выбор счета, который был добавлен, но не пробит.
	 */
	public function actionChooseExpenses($patient_id)
	{
		self::disableScripts();
		
		$modelPaid_Expenses=new Paid_Expenses('paid.cashAct.search');
		
		if(Yii::app()->request->getPost('formSearchExpenses'))
		{ //validate CActiveForm
			echo CActiveForm::validate($modelPaid_Expenses);
			Yii::app()->end();
		}
		
		$modelPaid_Expenses->patient_id=$patient_id;
		$modelPaid_Expenses->attributes=Yii::app()->request->getPost('Paid_Expenses');
		$modelPaid_Expenses->hashForm=substr(md5(uniqid("", true)), 0, 4);
		
		if(!Yii::app()->request->getParam('gridSelectExpenses') && strlen($modelPaid_Expenses->dateEnd)>0)
		{
			$modelPaid_Expenses->dateEnd.=' 23:59:59';
		}
		
		if(!Yii::app()->request->getParam('gridSelectExpenses'))
		{ //первый заход в этот экшн
			$modelPaid_Expenses->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$this->renderPartial('gridChooseExpenses', ['modelPaid_Expenses'=>$modelPaid_Expenses], false, true);
	}
	
	/**
	 * Используется ответом на ajax-запрос при выборе услуги (
	 * нажатии по записи в таблице).
	 * Смотри classSelectServices() в paid.js
	 * @param $code код услуги из хранилища, по которой был произведен двойной клик.
	 */
	public function actionSelectDoctors($code)
	{
		self::disableScripts();
		Yii::app()->clientScript->scriptMap['jquery.yiigridview.js']=false;
		$recordPaid_Service=Paid_Services::model()->find('code=:code', [':code'=>$code]);
		//взяли id группы
		
		$criteria=new CDbCriteria;
		$criteria->select='t.last_name, t.first_name, t.middle_name';
		$criteria->with=['groups'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->condition='groups.paid_service_group_id=:group_id';
		$criteria->params=[':group_id'=>$recordPaid_Service->paid_service_group_id];
		$criteria->distinct=true;
		$criteria->group='t.id';
		$modelDoctors=new Doctors('paid.cashAct.search');
		
		$modelDoctors->attributes=Yii::app()->request->getPost('Doctors');
		$modelDoctors->first_name=strtolower($modelDoctors->first_name);
		
		$criteria->compare('lower(t.first_name)', mb_strtolower($modelDoctors->first_name, 'UTF-8'), true);
		$criteria->compare('lower(t.last_name)', mb_strtolower($modelDoctors->last_name, 'UTF-8'), true);
		$criteria->compare('lower(t.middle_name)', mb_strtolower($modelDoctors->middle_name, 'UTF-8'), true);
		
		if(!Yii::app()->request->getParam('gridSelectDoctor'))
		{ //первый заход в этот экшн
			$modelDoctors->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$dataProvider=new CActiveDataProvider($modelDoctors, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Doctors::PAGE_SIZE]]);
		$this->renderPartial('gridSelectDoctors', ['modelDoctors'=>$modelDoctors, 'dataProvider'=>$dataProvider], false, true);
	}
	
	/**
	 * 
	 */
	public function actionTempPrepareOrder()
	{
		
	}
	
	/**
	 * Используется при ajax-запросе, который инициализируется при нажатии
	 * на выбранный счет
	 * Смотри classChooseExpenses() в paid.js
	 */
	public function actionChooseExpenseServices($expense_number)
	{
		self::disableScripts();
		Yii::app()->clientScript->scriptMap['jquery.yiigridview.js']=false; //уже есть одна CGridView на странице
		
		$recordPaid_Expenses=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		if($recordPaid_Expenses===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
	
		$criteria=new CDbCriteria();
		$criteria->condition='paid_order_id=:paid_order_id';
		$criteria->params=[':paid_order_id'=>$recordPaid_Expenses->paid_order_id];
//		$criteria->distinct=true;
		
		$modelPaid_Order_Details=new Paid_Order_Details('paid.cashAct.search');
		$modelPaid_Order_Details->attributes=Yii::app()->request->getParam('Paid_Order_Details');
		
		if(!Yii::app()->request->getParam('gridChooseExpenseServices'))
		{ //первый заход в этот экшн (не обработка пагинатора, сортировки и прочего)
			$modelPaid_Order_Details->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$dataProvider=new CActiveDataProvider($modelPaid_Order_Details, ['criteria'=>$criteria, 'pagination'=>['pageSize'=>Paid_Order_Details::PAGE_SIZE]]);
		
		$this->renderPartial('chooseExpenseServices', ['modelPaid_Order_Details'=>$modelPaid_Order_Details, 'dataProvider'=>$dataProvider], false, true);
	}
	
	/**
	 * Удаление счёта и его заказа (который еще не включен в платёж и не оплачен, т.е. status=0)
	 */
	public function actionDeleteExpense($paid_expense_id)
	{
		$recordExpense=Paid_Expenses::model()->findByPk($paid_expense_id);
		
		if($recordExpense===null && $recordExpense->status==Paid_Expenses::NOT_PAID) //существует и не включен в счёт
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			if(Paid_Orders::model()->deleteByPk($recordExpense->paid_order_id)
			&& Paid_Expenses::model()->deleteByPk($paid_expense_id))
			{
				Paid_Order_Details::model()->deleteAll('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordExpense->paid_order_id]);
				$transaction->commit();
				Yii::app()->end();
			}
			throw new CHttpException(404, 'Ошибка в запросе к БД.');
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	/**
	 * Удаление услуги (записи из paid_order_details) из сформированного заказа
	 * use in CGridView
	 */
	public function actionDeleteExpenseService($paid_order_detail_id)
	{
		$recordPaid_Order_Details=Paid_Order_Details::model()->findByPk($paid_order_detail_id);
		
		if($recordPaid_Order_Details===null)
		{
			throw new CHttpException(404, 'Такой услуги в заказе не существует.');
		}
		$recordPaid_Expense=Paid_Expenses::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordPaid_Order_Details->paid_order_id]);
		// выбранный заказ
		$recordPaid_Expense->price-=$recordPaid_Order_Details->service->price;
		
		$transaction=Yii::app()->db->beginTransaction();
		try
		{ //изменение стоимости счёта при удалении услуги из заказа
			if($recordPaid_Expense->save() && $recordPaid_Order_Details->deleteByPk($paid_order_detail_id))
			{
				$transaction->commit();
				Yii::app()->end();
			}
			throw new CHttpException(404, 'Ошибка в запросе к БД.');
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	/**
	 *	Добавление заказа и его счета (сформировать заказ)
	 * classSelectServices() из paid.js (ajax-запрос)
	 * @param $scenario integer 0 - редактирование, 1 - создание
	 */
	public function actionOrderForm($scenario, $order_id=null)
	{
		if(!Yii::app()->request->isAjaxRequest)
		{ //только AJAX запросы.
			throw new CHttpException(404, 'Ошибка в запросе');
		}
		
		/**
		 * Сформированный заказ, состоящий их услуг и доктора (массив данных).
		 * Из этих данных в последующем будут формироваться направления (группировка по группам и врачам из
		 * данного массива)
		 */
		$ordersForm=Yii::app()->request->getPost('orderForm')? Yii::app()->request->getPost('orderForm'): [];
//		if($ordersForm!==null) //заказ отправлен
//		{
			$modelPaid_Orders=new Paid_Orders('paid.cashAct.create'); //создаем заказ.
//			$modelPaid_Orders->name=null; //сомнительный параметр, в будущем удалить
			$modelPaid_Orders->patient_id=Yii::app()->request->getPost('patientId');
			$modelPaid_Orders->user_create_id=Yii::app()->user->id;
			$modelPaid_Orders->order_number=Paid_Orders::generateRandNumber(); //генерация номера заказа
			$modelPaid_Orders->number_contract=Paid_Orders::contractSequenceNumber();
			$transaction=Yii::app()->db->beginTransaction();
			try
			{
				if($scenario==0)
				{ // если заказ повешен на редактирование
					Paid_Orders::model()->deleteByPk($order_id);
					Paid_Order_Details::model()->deleteAll('paid_order_id=:order_id', [':order_id'=>$order_id]);
					Paid_Expenses::model()->deleteAll('paid_order_id=:order_id', [':order_id'=>$order_id]);
				}
				
				$errorCount=0;
				while(true)
				{
					$errorCount++;
					if($modelPaid_Orders->save()) // при первой успешной валидации сохраняем.
					{
						break;
					}
					if($errorCount>50)
					{ //если слишком много прокруток, и уникальность не отрабатывает, то надо менять алгоритм генерации номера заказа
						throw new CHttpException(404, 'Ошибка в валидации заказа (уникальность номера)');
					}
				}
				$priceSum=0;
				$modelPaid_Expenses=new Paid_Expenses('paid.cashAct.create');
				$modelPaid_Expenses->date=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
//				$modelPaid_Expenses->price=ParseMoney::encodeMoney(Yii::app()->request->getPost('priceSum'));
				$modelPaid_Expenses->price=$priceSum; //дальше обновляем по сумме всех услуг, добавленных в заказ.
				$modelPaid_Expenses->paid_order_id=Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq');
				$modelPaid_Expenses->status=Paid_Expenses::NOT_PAID; //еще не оплачен
				$modelPaid_Expenses->expense_number=Paid_Expenses::expenseSequenceNumber();
				$modelPaid_Expenses->user_create_id=Yii::app()->user->id;
				
				if(!$modelPaid_Expenses->save())
				{
					throw new CHttpException(404, 'Ошибки валидации в запросе (создание счёта).');
				}
				
				$modelPaid_Order_Details=new Paid_Order_Details('paid.cashAct.create'); //детализация заказа
				$modelPaid_Order_Details->paid_order_id=Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq');

				// Добавление услуги и доктора в заказ. M:M
				foreach($ordersForm as $value)
				{
					$recordPaid_Services=Paid_Services::model()->findByPk($value['serviceId']);
					
					if($recordPaid_Services===null)
					{
						throw new CHttpException(404, 'Услуга не найдена.');
					}
					
					$modelPaid_Order_Details->paid_service_id=$value['serviceId'];
					$modelPaid_Order_Details->doctor_id=$value['doctorId'];
					$modelPaid_Order_Details->price=$recordPaid_Services->price; // сохраняем для данной услуги в заказе её стоимость на момент формирования заказа.
					$priceSum+=$recordPaid_Services->price;
					
					if(!$modelPaid_Order_Details->save())
					{
						throw new CHttpException(404, 'Ошибки валидации в запросе (детализация заказа).');
					}
					$modelPaid_Order_Details->isNewRecord=true;
				}
				$recordPaid_Expenses=Paid_Expenses::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq')]);
				
				if($recordPaid_Expenses===null)
				{
					throw new CHttpException(404, 'Не удалось найти счёт.');
				}
				
				$recordPaid_Expenses->price=$priceSum;

				if(!$recordPaid_Expenses->save())
				{
					throw new CHttpException(404, 'Не удалось сохранить общую сумму счёта.');
				}
				
				$transaction->commit();
			
				Yii::app()->end(Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq')); //успех, разблокируем кнопку "Пробить"				
		
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
//		}
//		else { // пустой заказ
//			echo -1;
//			Yii::app()->end();
//		}
	}
	
	public function actionReturnServicePrice($service_id)
	{
		$recordService=Paid_Services::model()->findByPk($service_id);
		
		if($recordService===null)
		{
			echo 0;
		}
		else
		{
			echo ParseMoney::decodeMoney($recordService->price);
		}
	}
	
	/**
	 * Удаление заказа со счетом (удаление сформированного заказа)
	 * @param integer $paid_order_id #ID заказа, который мы будем отменять (удалять), вместе с его счетом.
	 */
	public function actionDeleteOrderForm($paid_order_id)
	{
		if(!Yii::app()->request->isAjaxRequest)
		{
			throw new CHttpException(404, 'Неверный запрос.');
		}
		
		if(!Paid_Orders::model()->findbyPk($paid_order_id))
		{
			throw new CHttpException(404, 'Заказ не найден.');
		}
		
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			Paid_Order_Details::model()->deleteAll('paid_order_id=:id', [':id'=>$paid_order_id]);
			Paid_Orders::model()->deleteAll('paid_order_id=:id', [':id'=>$paid_order_id]);
			Paid_Expenses::model()->deleteAll('paid_order_id=:id AND status=' . Paid_Expenses::NOT_PAID, [':id'=>$paid_order_id]);
			$transaction->commit();
			Yii::app()->end('success');
		}
		catch (Exception $e) 
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	/**
	 * Подготовка услуг для дальнейшей пробивки чека (без сохранения
	 * заказа и счёта в хранилище, т.к. они уже там есть)
	 * "Выбрать счёт"
	 * @param integer $expense_number номер заказа
	 */
	public function actionPrepareOrder($expense_number)
	{
		$recordPaid_Expense=Paid_Expenses::model()->find('expense_number=:expense_number', [':expense_number'=>$expense_number]);
		if($recordPaid_Expense===null)
		{
			throw new CHttpException(404, 'Счёт не найден.');
		}
		
		$recordPaid_Orders=Paid_Orders::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordPaid_Expense->paid_order_id]);
		if($recordPaid_Orders===null)
		{
			throw new CHttpException(404, 'Заказ не найден.');
		}
		echo $recordPaid_Orders->paid_order_id;
	}
	
	/**
	 * Пробивка чека (оплата и закрытие счёта, формирование направлений)
	 * @param integer $paid_order_id #ID заказа,
	 */
	public function actionPunch($paid_order_id, $patient_id)
	{
		if(!Yii::app()->request->isAjaxRequest)
		{
			throw new CHttpException(404, 'Неверный запрос.');
		}
		
		$recordPaid_Order=Paid_Orders::model()->findByPk($paid_order_id);
		if($recordPaid_Order===null)
		{
			throw new CHttpException(404, 'Заказ не найден.');
		}
		
		$recordPaid_Expenses=Paid_Expenses::model()->find('paid_order_id=:order_id', [':order_id'=>$recordPaid_Order->paid_order_id]);
		if($recordPaid_Expenses===null)
		{
			throw new CHttpException(404, 'Cчёт не найден.');
		}
		
		if($recordPaid_Expenses->status==Paid_Expenses::PAID)
		{
			throw new CHttpException(404, 'Счёт уже был оплачен. Транзакция отменена.');
		}
		
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			$modelPaid_Payments=new Paid_Payments();
			$modelPaid_Payments->paid_expense_id=$recordPaid_Expenses->paid_expense_id;
			$modelPaid_Payments->date_create=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
			$modelPaid_Payments->date_delete=null;
			$modelPaid_Payments->reason_delete=null;
			$modelPaid_Payments->user_delete_id=null;
			$recordPaid_Expenses->status=Paid_Expenses::PAID; //счёт оплачен
			
			if(!$modelPaid_Payments->save())
			{
				throw new CHttpException(404, 'Ошибка в проведении платежа по счёту. Транзакция отменена.');
			}
			
			if(!$recordPaid_Expenses->save())
			{
				throw new CHttpException(404, 'Ошибка в запросе смены статуса счета. Транзакция отменена.');
			}
			//ActiveRecord не отрабатывает (сложный запрос для AR, группировки), поэтому запрос напрямую.
			//TODO IN MODEL
			$sql='SELECT service.paid_service_group_id, t.doctor_id
				  FROM "paid"."paid_services" service, "paid"."paid_order_details" t
				  WHERE service.paid_service_id=t.paid_service_id
				  AND t.paid_order_id=:paid_order_id
				  GROUP BY service.paid_service_group_id, t.doctor_id;
			';
			$command=Yii::app()->db->createCommand($sql);
			$command->bindParam(':paid_order_id', $paid_order_id, PDO::PARAM_INT);
			$groupRows=$command->query()->readAll();
			
			$sql='SELECT t.paid_service_id, t.doctor_id
				  FROM "paid"."paid_order_details" t, "paid"."paid_services" service
				  WHERE service.paid_service_group_id=:group_id
				  AND service.paid_service_id=t.paid_service_id
				  AND t.doctor_id=:doctor_id
				  AND t.paid_order_id=:paid_order_id;
			';//получаем все услуги от группировок, последовательно
			$command=Yii::app()->db->createCommand($sql);
			
			//считали группы (связка группа_услуги - номер врача)
			$referrals = array();
			$index = 0;
			foreach($groupRows as $groupRow)
			{ //считываем группу (ее услуги), создаем направление на основе одной группы
				$command->bindParam(':paid_order_id', $paid_order_id, PDO::PARAM_INT);
				$command->bindParam(':group_id', $groupRow['paid_service_group_id'], PDO::PARAM_INT);
				$command->bindParam(':doctor_id', $groupRow['doctor_id'], PDO::PARAM_INT);
				$serviceRows=$command->query()->readAll(); //считали все услуги из заказа для формирования одного направления
				
				$modelPaid_Referrals=new Paid_Referrals();
				$modelPaid_Referrals->paid_order_id=$paid_order_id;
				$modelPaid_Referrals->date=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
				$modelPaid_Referrals->patient_id=$patient_id; //избыточность, но зато меньше связей.
				$modelPaid_Referrals->doctor_id=$groupRow['doctor_id'];
				$modelPaid_Referrals->referral_number=Paid_Orders::generateRandNumber();
				$modelPaid_Referrals->status=null; //очень сомнительный параметр
				$modelPaid_Referrals->save(); //сохранили направление. Теперь добавляем в него услуги.
				
				foreach($serviceRows as $serviceRow)
				{ //добавление услуг в направление
					$modelPaid_Referrals_Details=new Paid_Referrals_Details();
					$modelPaid_Referrals_Details->paid_service_id=$serviceRow['paid_service_id'];
					
					$recordPaid_Services=Paid_Services::model()->findByPk($serviceRow['paid_service_id']);
					
					if($recordPaid_Services===null)
					{
						throw new CHttpException(404, 'Не существует выбранной услуги. Ошибка на уровне создания услуги в направлении.');
					}
					
					// берем старую цену из Order_Details
					$recordPaid_Order_Details=Paid_Order_Details::model()->find('paid_order_id=:paid_order_id AND paid_service_id=:paid_service_id AND doctor_id=:doctor_id', [
						':paid_order_id'=>$paid_order_id,
						':paid_service_id'=>$recordPaid_Services->paid_service_id,
						':doctor_id'=>$modelPaid_Referrals->doctor_id,
					]);
					
					if($recordPaid_Order_Details===null) // именно такая услуга заказа не была
					{
						throw new CHttpException('Заказ, по которому генерируются направления был испорчен.');
					}
					
					$modelPaid_Referrals_Details->price=$recordPaid_Order_Details->price; // берем старую цену из заказа
					
//					$modelPaid_Referrals_Details->doctor_id=$serviceRow['doctor_id'];
					$modelPaid_Referrals_Details->paid_referral_id=Yii::app()->db->getLastInsertID('paid.paid_referrals_paid_referrals_id_seq');
					
					if(!$modelPaid_Referrals_Details->save())
					{
						throw new CHttpException(404, 'Ошибка в запросе создания направлений. Транзакция отменена.');
					}
				} //сформировали одно направление с услугами, надо печатать их где-то тут. 
				//TODO!!!!!!!!!!!!!!!!!TODO!!!!!!!!!!ПЕЧАТЬ направлений где-то тут
				//Сформировать JSON массив и отправить в JS
				$referrals[$index]=Yii::app()->db->getLastInsertID('paid.paid_referrals_paid_referrals_id_seq');
				$index++;
			}
			$transaction->commit();
			
			echo CJSON::encode($referrals);
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
	
	/**
	 * Печать направления при пробитии чека
	 * @param integer $paid_referral_id #ID направления
	 */
	public function actionPrintReferral($paid_referral_id)
	{
		$recordReferral=Paid_Referrals::model()->findByPk($paid_referral_id);
		
		if($recordReferral===null)
		{
			throw new CHttpException(404, 'Такого направления не существует.');
		}
		
		$recordOrder=Paid_Orders::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordReferral->paid_order_id]);
		
		if($recordOrder===null)
		{
			throw new CHttpException(404, 'Заказа в данном направлении не существует.');
		}
		
		$recordExpense=Paid_Expenses::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordOrder->paid_order_id]);
		
		if($recordExpense===null)
		{
			throw new CHttpException(404, 'В данном заказе не создан счёт.');
		}
		
		$recordPatient=Patients::model()->find('patient_id=:patient_id', [':patient_id'=>$recordOrder->patient_id]);
		
		if($recordPatient===null)
		{
			throw new CHttpException(404, 'Такого пациента не существует.');
		}
		
		//TODO TODO TODO НЕПОНЯТНО КАКУЮ КАРТУ ПОДТЯГИВАЕТ МОДУЛЬ!!!! ИХ МОЖЕТ БЫТЬ МНОЖЕСТВО
		$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', [':patient_id'=>$recordPatient->patient_id]);
		
		if($recordPaid_Medcard===null)
		{
			throw new CHttpException(404, 'Такой ЭМК не существует.');
		}
		
		$recordReferrals_Details=Paid_Referrals_Details::model()->findAll('paid_referral_id=:paid_referral_id', [':paid_referral_id'=>$paid_referral_id]);
		
		if(empty($recordReferrals_Details))
		{
			throw new CHttpException(404, 'В данном направлении отсутствуют услуги.');
		}
		
		$modelReferrals_Details=new Paid_Referrals_Details();
		
		$this->renderPartial('printReferral', ['recordExpense'=>$recordExpense, 'recordPaid_Medcard'=>$recordPaid_Medcard, 'recordPatient'=>$recordPatient, 'paid_referral_id'=>$paid_referral_id, 'modelReferrals_Details'=>$modelReferrals_Details, 'recordReferral'=>$recordReferral, 'recordReferrals_Details'=>$recordReferrals_Details], false, true);
	}
	
	/**
	 * Печать счета при нажатии на "Сформировать заказ"
	 * или "Выбрать" из выбора счёта.
	 * @param integer $paid_order_id номер заказа
	 */
	public function actionPrintExpense($paid_order_id)
	{
		$recordPaid_Order=Paid_Orders::model()->findByPk($paid_order_id);
		
		if($recordPaid_Order===null)
		{
			throw new CHttpException(404, 'Такой заказ не существует.');
		}
		
		$recordPaid_Expense=Paid_Expenses::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$paid_order_id]);
		
		if($recordPaid_Expense===null)
		{
			throw new CHttpException(404, 'Такой счёт не существует.');
		}
		
		$recordPatient=Patients::model()->find('patient_id=:patient_id', [':patient_id'=>$recordPaid_Order->patient_id]);
		
		if($recordPatient===null)
		{
			throw new CHttpException(404, 'Такого пациента не существует.');
		}
		
		$recordPaid_Medcard=Paid_Medcards::model()->find('patient_id=:patient_id', [':patient_id'=>$recordPaid_Order->patient_id]);
		
		if($recordPaid_Medcard===null)
		{
			throw new CHttpException(404, 'Такой медкарты не существует.');
		}
		
		$modelOrder_Details=new Paid_Order_Details();
		
//		$this->layout='print';
//		$mPdf=Yii::app()->ePdf->mpdf();
//		$mPdf->WriteHTML($this->renderPartial('printExpense', ['paid_order_id'=>$paid_order_id, 'modelOrder_Details'=>$modelOrder_Details, 'recordPaid_Expense'=>$recordPaid_Expense, 'recordPatient'=>$recordPatient, 'recordPaid_Medcard'=>$recordPaid_Medcard], true, true));
//		$mPdf->Output();
		
		$this->renderPartial('printExpense', ['paid_order_id'=>$paid_order_id, 'modelOrder_Details'=>$modelOrder_Details, 'recordPaid_Expense'=>$recordPaid_Expense, 'recordPatient'=>$recordPatient, 'recordPaid_Medcard'=>$recordPaid_Medcard], false, true);
	}
	
	/**
	 * Печать договора на оказание услуг.
	 * @param integer $order_id #ID заказа
	 */
	public function actionPrintContract($order_id)
	{
		$recordOrder=Paid_Orders::model()->findByPk($order_id);
		
		if($recordOrder===null)
		{
			throw new CHttpException(404, 'Такого заказа не существует.');
		}
		
		$recordExpense=Paid_Expenses::model()->find('paid_order_id=:paid_order_id', [':paid_order_id'=>$recordOrder->paid_order_id]);
		
		if($recordExpense===null)
		{
			throw new CHttpException(404, 'Такого счёта не существует.');
		}
		
		/**
		 * пока один документ, TODO множество
		 */
		$recordDocuments=Patient_Documents::model()->find('patient_id=:patient_id', [':patient_id'=>$recordOrder->patient_id]);
		
		if($recordDocuments===null)
		{
			throw new CHttpException(404, 'Отсутствует документ у пациента.');
		}
		
		$this->layout='print';
		$mPdf=Yii::app()->ePdf->mpdf();
		$mPdf->WriteHTML($this->render('ContractPdf', ['recordDocuments'=>$recordDocuments, 'recordOrder'=>$recordOrder, 'recordExpense'=>$recordExpense], true));
		$mPdf->Output();
//		$this->layout='print';
//		$this->render('ContractPdf', []);
	}
}