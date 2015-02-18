<?php
/**
 * Виджет для вывода шапки
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class HeaderWidget extends Widget
{
	public function run()
	{
		$this->render('headerWidget');
	}
}