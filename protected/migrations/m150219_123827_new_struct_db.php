<?php
/**
 * Миграция на выделение сущности "пациент" и связных с ней таблиц.
 * Без FK
 * При создании таблиц и ее атрибутов обязательно указывать схему, в которую добавляем таблицы.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class m150219_123827_new_struct_db extends CDbMigration
{
	public function up()
	{
		$connection=Yii::app()->db;
		$sql=<<<HERE
				ALTER TABLE "mis"."medcards" ADD COLUMN "patient_id" integer DEFAULT NULL;
HERE;
		$command=$connection->createCommand($sql);
		$command->execute();
		
		$sql=<<<HERE
				CREATE TABLE IF NOT EXISTS "mis"."patients" --Пациенты
				(
					"patient_id" serial NOT NULL,
					"first_name" character varying(255) NOT NULL,
					"middle_name" character varying(255) NOT NULL,
					"last_name" character varying(255) NOT NULL,
					"gender" character varying(100) NOT NULL, --1/2 (Мужской/женский)
					"birthday" date NOT NULL, --День рождения
					"address_reg" character varying(255) DEFAULT NULL, --Адрес регистрации
					"address" character varying(255) DEFAULT NULL, --Адрес фактического проживания
					"snils" character varying(255) DEFAULT NULL, --СНИЛС
					"invalid_group" character varying(255) DEFAULT NULL, --группа инвалидности
					"phone_number" character varying(255) DEFAULT NULL, --Номер телефона
					"profession" character varying(255) DEFAULT NULL, --Профессия
					"work_address" character varying(255) DEFAULT NULL, --Адрес работы
					"work_place" character varying(255) DEFAULT NULL, --Место работы
					"address_str" text DEFAULT NULL, --for search
					"address_reg_str" text DEFAULT NULL, --for search
					"create_timestamp" TIMESTAMPTZ NOT NULL, --Время создания записи (пациента)
					PRIMARY KEY(patient_id)
				);
HERE;
		$command=$connection->createCommand($sql);
		$command->execute();
		
		$sql=<<<HERE
				CREATE TABLE IF NOT EXISTS "mis"."patient_documents" --Документы гражданина
				(
					"patient_document_id" serial NOT NULL,
					"serie" character varying(255) DEFAULT NULL,
					"number" character varying(255) DEFAULT NULL,
					"who_gived" character varying(255) DEFAULT NULL,
					"date_gived" character varying(255) DEFAULT NULL,
					"type" integer DEFAULT NULL,
					"patient_id" integer DEFAULT NULL, --FK (table mis.patients)
					PRIMARY KEY(patient_document_id)
				);
HERE;
		$command=$connection->createCommand($sql);
		$command->execute();
		
		$sql=<<<HERE
				CREATE TABLE IF NOT EXISTS "mis"."patient_contacts"
				(
					"patient_contact_id" serial NOT NULL,
					"patient_id" integer DEFAULT NULL, --FK (table mis.patients)
					"type" integer DEFAULT NULL, --Тип контакта (1/2) 1-Мобильный, 2-домашний
					"value" character varying(255) DEFAULT NULL,
					PRIMARY KEY(patient_contact_id)
				);
HERE;
	
		$command=$connection->createCommand($sql);
		$command->execute();
	}
	public function down()
	{
	}
}