<?php
/**
 * Виджет для вывода футера
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class FooterWidget extends Widget
{
	public function run()
	{
		$this->render('footerWidget');
	}
}