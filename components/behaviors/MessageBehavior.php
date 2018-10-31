<?php
namespace app\components\behaviors;

use yii\base\Behavior;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Manages message behavior.
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class MessageBehavior extends Behavior
{
	/**
	 * @var Controller $controller
	 */
	public $controller;

	/**
	 * Show an notice message
	 *
	 * @param string $message
	 */
	public function notice(string $message)
	{
		echo $this->controller->ansiFormat($message, Console::FG_GREY) . "\n";
	}

	/**
	 * Show an info message
	 *
	 * @param string $message
	 */
	public function info(string $message)
	{
		echo $this->controller->ansiFormat($message, Console::FG_CYAN) . "\n";
	}

	/**
	 * Show an date message
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function data(string $key, string $value)
	{
		echo $this->controller->ansiFormat($key, Console::FG_CYAN) . ': ' . $value . "\n";
	}

	/**
	 * Show a row message
	 *
	 * @param array $values
	 */
	public function row(array $values)
	{
		if (!empty($values)) {
			$count = 0;
			foreach ($values as $value) {
				echo (($count++ === 0) ? '' : ' - ') . $this->controller->ansiFormat($value, Console::FG_GREY);
			}
			echo "\n";
		}
	}

	/**
	 * Show a warning message
	 *
	 * @param string $message
	 */
	public function warning(string $message)
	{
		echo $this->controller->ansiFormat($message, Console::FG_YELLOW) . "\n";
	}

	/**
	 * Show an error message
	 *
	 * @param string $message
	 */
	public function error(string $message)
	{
		echo $this->controller->ansiFormat('Error: ', Console::FG_RED) . $message . "\n";
	}
}