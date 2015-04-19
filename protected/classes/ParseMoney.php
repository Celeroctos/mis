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
		$m=(float)$money*100;
		
		return (int)$m;
	}
	
	/**
	 * Метод для вывода на экран денежной суммы (float)
	 * @param int $money
	 * @return float $money
	 */
	public static function decodeMoney($money)
	{
		$money=(float)$money/100;
		
		return number_format($money,2, '.', ''); //выводить всегда 2 знака после запятой
	}
}