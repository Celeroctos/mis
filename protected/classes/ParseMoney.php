<?php
/**
 * Класс для работы с деньгами.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class ParseMoney
{
	/**
	 * Метод для преобразования денег
	 * и дальнейшей записи их в хранилище.
	 * @param mixed $money
	 * @return int $money
	 */
	public static function encodeMoney($money)
	{
		$m=$money*100;
		
		return number_format($m, 0, '.', '');
	}
	
	/**
	 * Метод для вывода на экран денежной суммы (float)
	 * @param int $money
	 * @return float $money
	 */
	public static function decodeMoney($money)
	{
		return number_format((float)$money/100, 2, '.', ''); //выводить всегда 2 знака после запятой
	}
}