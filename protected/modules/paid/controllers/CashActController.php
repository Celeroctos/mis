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
		$modelPaid_Service=new Paid_Services('paid.cash.select');
		$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
		
		$modelDoctors=new Doctors();
		if(!Yii::app()->request->getParam('gridSelectServices'))
		{
			$modelPaid_Service->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$this->renderPartial('gridSelectServices', ['modelPaid_Service'=>$modelPaid_Service, 'modelDoctors'=>$modelDoctors], false, true);
	}
	
	/**
	 * Выбор счета, который был добавлен, но не пробит.
	 */
	public function actionChooseExpenses()
	{
		self::disableScripts();
		
	}
	
	/**
	 * Используется ответом на ajax-запрос при выборе услуги (двойном
	 * нажатии по записи в таблице).
	 * Смотри classSelectServices() в paid.js
	 * @param $code код услуги из хранилища, по которой был произведен двойной клик.
	 */
	public function actionChooseDoctor($code)
	{
		self::disableScripts();
		$recordPaid_Service=Paid_Services::model()->find('code=:code', [':code'=>$code]);
		//взяли id группы
		
		$criteria=new CDbCriteria;
		$criteria->select='t.last_name, t.first_name, t.middle_name';
		$criteria->with=['groups'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->condition='groups.paid_service_group_id=:group_id';
		$criteria->params=[':group_id'=>$recordPaid_Service->paid_service_group_id];
		$criteria->group='t.id';
		$modelDoctors=new Doctors('paid.cashAct.search');
		$modelDoctors->attributes=Yii::app()->request->getParam('Doctors');
		
		if(!Yii::app()->request->getParam('gridSelectDoctor'))
		{ //первый заход в этот экшн
			$modelDoctors->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$dataProvider=new CActiveDataProvider($modelDoctors, ['criteria'=>$criteria]);
		$this->renderPartial('gridChooseDoctor', ['modelDoctors'=>$modelDoctors, 'dataProvider'=>$dataProvider], false, true);
	}
	
	/**
	 *	Добавление заказа и его счета (сформировать заказ)
	 * classSelectServices() из paid.js (ajax-запрос)
	 */
	public function actionOrderForm()
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
		$ordersForm=Yii::app()->request->getPost('orderForm');
		if($ordersForm!==null) //заказ отправлен
		{
			$modelPaid_Orders=new Paid_Orders('paid.cashAct.create'); //создаем заказ.
//			$modelPaid_Orders->name=null; //сомнительный параметр, в будущем удалить
			$modelPaid_Orders->patient_id=Yii::app()->request->getPost('patient_id');
			$modelPaid_Orders->user_create_id=Yii::app()->user->id;
			$modelPaid_Orders->order_number=Paid_Orders::generateRandNumber(); //генерация номера заказа
			
			$transaction=Yii::app()->db->beginTransaction();
			try
			{
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
						throw new CHttpException(404, 'Ошибка в валидации заказа');
					}
				}
				
				$modelPaid_Expenses=new Paid_Expenses('paid.cashAct.create');
				$modelPaid_Expenses->date=Yii::app()->dateformatter->format('yyyy-MM-dd HH:mm:ss', time());
				$modelPaid_Expenses->price=ParseMoney::encodeMoney(Yii::app()->request->getPost('priceSum'));
				$modelPaid_Expenses->paid_order_id=Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq');
				$modelPaid_Expenses->status=Paid_Expenses::NOT_PAID; //еще не оплачен
				
				if(!$modelPaid_Expenses->save())
				{
					throw new CHttpException(404, 'Ошибки валидации в запросе (создание счёта)');
				}
				
				$modelPaid_Order_Details=new Paid_Order_Details('paid.cashAct.create'); //детализация заказа
				$modelPaid_Order_Details->paid_order_id=Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq');

				foreach($ordersForm as $value)
				{
					$modelPaid_Order_Details->paid_service_id=$value['serviceId'];
					$modelPaid_Order_Details->doctor_id=$value['doctorId'];

					if(!$modelPaid_Order_Details->save())
					{
						throw new CHttpException(404, 'Ошибки валидации в запросе (детализация заказа)');
					}
					$modelPaid_Order_Details->isNewRecord=true;
				}
				
				$transaction->commit();
				Yii::app()->end(Yii::app()->db->getLastInsertID('paid.paid_orders_paid_order_id_seq')); //успех, разблокируем кнопку "Пробить"
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
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
	 * Пробивка чека (оплата и закрытие счёта, формирование направлений)
	 * @param integer $paid_order_id #ID заказа,
	 */
	public function actionPunch($paid_order_id, $patient_id)
	{
		if(!Yii::app()->request->isAjaxRequest)
		{
			throw new CHttpException(404, 'Неверный запрос.');
		}
		
		$recordPaid_Order=Paid_Orders::model()->findbyPk($paid_order_id);
		$recordPaid_Expenses=Paid_Expenses::model()->find('paid_order_id=:order_id', [':order_id'=>$recordPaid_Order->paid_order_id]);
		if($recordPaid_Order===null || $recordPaid_Expenses===null)
		{
			throw new CHttpException(404, 'Заказ и(или) счёт не найден(ы)');
		}
		
		$transaction=Yii::app()->db->beginTransaction();
		try
		{
			$modelPaid_Payments=new Paid_Payments();
			$modelPaid_Payments->paid_expense_id=$recordPaid_Expenses->paid_expense_id;
			$modelPaid_Payments->date_delete=null;
			$modelPaid_Payments->reason_date_delete=null;
			$modelPaid_Payments->user_delete_id=null;
			$modelPaid_Payments->save();
			
			$sql='SELECT service.paid_service_group_id, t.doctor_id
				  FROM "paid"."paid_services" service, "paid"."paid_order_details" t
				  WHERE service.paid_service_id=t.paid_service_id
				  AND t.paid_order_id=:paid_order_id
				  GROUP BY service.paid_service_group_id, t.doctor_id;
			';
			$command=Yii::app()->db->createCommand($sql);
			$command->bindParam(':paid_order_id', $paid_order_id, PDO::PARAM_INT);
			$groupRows=$command->query()->readAll();
//			
//			$modelPaid_Referrals_Details=new Paid_Referrals_Details();
//			$modelPaid_Referrals_Details->paid_order_id=$paid_order_id;
			
			$sql='SELECT t.paid_service_id, t.doctor_id
				  FROM "paid"."paid_order_details" t, "paid"."paid_services" service
				  WHERE service.paid_service_group_id=:group_id
				  AND service.paid_service_id=t.paid_service_id
				  AND t.doctor_id=:doctor_id
				  AND t.paid_order_id=:paid_order_id;
			';//получаем все услуги от группировок, последовательно
			$command=Yii::app()->db->createCommand($sql);
			
			//считали группы (связка группа_услуги - номер врача)
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
				$modelPaid_Referrals->status=null; //очень сомнительный параметр
				$modelPaid_Referrals->save(); //сохранили направление. Теперь добавляем в него услуги.
				
				foreach($serviceRows as $serviceRow)
				{ //добавление услуг в направление
					$modelPaid_Referrals_Details=new Paid_Referrals_Details();
					$modelPaid_Referrals_Details->paid_service_id=$serviceRow['paid_service_id'];
//					$modelPaid_Referrals_Details->doctor_id=$serviceRow['doctor_id'];
					$modelPaid_Referrals_Details->paid_referral_id=Yii::app()->db->getLastInsertID('paid.paid_referrals_paid_referrals_id_seq');
					
					if(!$modelPaid_Referrals_Details->save())
					{
						throw new CHttpException(404, 'Ошибка в запросе создания направлений');
					}
				} //сформировали одно направление, надо печатать..
			}
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
		}
	}
}