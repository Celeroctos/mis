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
				CREATE TABLE IF NOT EXISTS "mis"."patients"
				(
					"patient_id" serial NOT NULL,
					"first_name" character varying(255) NOT NULL,
					"middle_name" character varying(255) NOT NULL,
					"last_name" character varying(255) NOT NULL,
					"gender" character varying(100) NOT NULL,
					"birthday" date DEFAULT NULL,
					"document_type" character varying(255) DEFAULT NULL, --Тип документа (паспорт и прочее)
					"document_serie" character varying(255) DEFAULT NULL, --Серия документа
					"document_number" character varying(255) DEFAULT NULL, --Номер документа
					"document_who_gived" character varying(255) DEFAULT NULL, --Кто выдал документ
					"document_date_gived" character varying(255) DEFAULT NULL, --Дата выдачи
					"address_reg" character varying(255) DEFAULT NULL, --Адрес регистрации
					"address" character varying(255) DEFAULT NULL, --Адрес фактического проживания
					"snils" character varying(255) DEFAULT NULL, --СНИЛС
					"invalid_group" character varying(255) DEFAULT NULL, --группа инвалидности
					"phone_number" character varying(255) DEFAULT NULL, --Номер телефона
					"profession" character varying(255) DEFAULT NULL, --Профессия
					"job_address" character varying(255) DEFAULT NULL, --Адрес работы
					"create_timestamp" TIMESTAMPTZ NOT NULL, --Время создания записи (пациента)
					PRIMARY KEY(patient_id)
				);
HERE;
		$command=$connection->createCommand($sql);
		$command->execute();
	}

	public function down()
	{
	}
}