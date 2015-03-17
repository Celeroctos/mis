<?php
/**
 * Структура БД (DDL) для модуля платных услуг.
 * Без FK
 * При создании таблиц и ее атрибутов обязательно указывать схему, в которую добавляем таблицы.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class m150215_142147_paid_module extends CDbMigration
{
	public function up()
	{
		$connection=Yii::app()->db;
		
		$sql="CREATE SCHEMA IF NOT EXISTS paid";
		$command=$connection->createCommand($sql);
		$command->execute();
			
            $sql=<<<HERE
                    CREATE TABLE IF NOT EXISTS "paid"."paid_service_groups"
                    (
                        "paid_service_group_id" serial NOT NULL,
                        "name" character varying(255) NOT NULL, --Имя группы
                        "p_id" integer DEFAULT NULL, --Родитель группы, NULL, если нету
                        PRIMARY KEY (paid_service_group_id)
                    );
HERE;
            $command=$connection->createCommand($sql);
            $command->execute();
            
            $sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_services"
					(
						"paid_service_id" serial NOT NULL,
						"paid_service_group_id" integer, --FK (table paid_service_groups)
						"name" character varying(255) NOT NULL, --Имя услуги
						"code" character varying(255) NOT NULL, --Код услуги
						"price" integer DEFAULT NULL, --цена
						"since_date" timestamptz, --действует с этой даты
						"exp_date" timestamptz, --действует по
						PRIMARY KEY(paid_service_id)
					);
HERE;
            $command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_services_doctors"
					(
						"paid_service_doctor_id" serial NOT NULL,
						"paid_service_group_id" integer NOT NULL, --FK (table paid_service_groups)
						"doctor_id" integer NOT NULL, --FK (table doctors)
						PRIMARY KEY(paid_service_doctor_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_orders"
					(
						"paid_order_id" serial NOT NULL,
						"name" character varying(255),
						"user_create_id" integer NOT NULL, --Пользователь, создавший заказ и в дальнейшем платёж
						"paid_expense_id" integer, --Номер счета, при статусе "новое" пустое значение, при статусе "включено в счет" ID счета
						PRIMARY KEY(paid_order_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			/*Таблица на самом деле является TEMP-хранилищем для создания направлений на её основе, можно чистить.*/
			/*Использовать как реестр услуг*/
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_order_details"
					(
						"paid_order_detail_id" serial NOT NULL,
						"paid_order_id" integer NOT NULL, --FK (table paid_orders)
						"paid_service_id" integer NOT NULL, --FK (table paid_services)
						PRIMARY KEY(paid_order_detail_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_referrals"
					(
						"paid_referrals_id" serial NOT NULL, --Уникальный номер направления
						"paid_order_id" integer NOT NULL, --FK (table paid_orders)
						"paid_medcard_id" integer NOT NULL, --FK (table paid_medcards)
						"date" TIMESTAMPTZ,
						"status" integer, --Сомнительно, возможно удаление (есть в paid_orders)
						PRIMARY KEY(paid_referrals_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_referrals_details"
					(
						"paid_referral_detail_id" serial NOT NULL,
						"paid_service_id" integer NOT NULL,
						"paid_referral_id" integer NOT NULL,
						PRIMARY KEY(paid_referral_detail_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_expenses"
					(
						"paid_expense_id" serial NOT NULL,
						"date" TIMESTAMPTZ, --Дата создания
						"price" integer NOT NULL, --Сумма счёта (умноженная на 100)
						"paid_order_id" integer NOT NULL, --FK (table paid_orders)
						"status" integer, --Оплачен/не оплачен (1/0)
						PRIMARY KEY(paid_expense_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_payments"
					(
						"paid_payment_id" serial NOT NULL,
						"paid_expense_id" integer NOT NULL, --FK (table paid_expenses)
						"date_delete" TIMESTAMPTZ, --Дата удаления платежа
						"reason_date_delete" TIMESTAMPTZ, --Причина удаления платежа
						"user_delete_id" integer, --FK (table users), Пользователь, удаливший платёж
						PRIMARY KEY(paid_payment_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql=<<<HERE
					CREATE TABLE IF NOT EXISTS "paid"."paid_medcards"
					(
						"paid_medcards_id" serial NOT NULL,
						"paid_medcard_number" character varying(255) DEFAULT NULL, --Номер карты
						"date_create" TIMESTAMPTZ NOT NULL, --Дата создания карты
						"enterprise_id" integer DEFAULT NULL, --FK (table enterprises)
						"patient_id" integer, --FK (table medcards). Привязка к основной (абстрактной) карте ЭМК
						PRIMARY KEY(paid_medcards_id)
					);
HERE;
			$command=$connection->createCommand($sql);
			$command->execute();
	}

	public function down()
	{
	}
}