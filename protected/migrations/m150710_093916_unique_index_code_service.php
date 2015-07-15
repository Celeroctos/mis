<?php
/**
 * Миграция на создание уникального индекса для кода услуги.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class m150710_093916_unique_index_code_service extends CDbMigration
{
	public function up()
	{
		$connection=Yii::app()->db;
		$sql='CREATE UNIQUE INDEX "unique_code_service" ON "paid"."paid_services"("code")';
		$command=$connection->createCommand($sql);
		$command->execute();
	}

	public function down()
	{
	}
}