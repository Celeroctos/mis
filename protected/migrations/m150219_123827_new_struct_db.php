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