<?php
namespace common\base;

use yii\console\Controller as BaseConsoleController;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Description of ConsoleController
 *
 * @author fuzz
 */
class ConsoleController extends BaseConsoleController
{
	public $deleteSource = false;
	public $outputFormat = 'syslog';

	/**
	 * Help message
	 */
	public function actionIndex()
	{
		$this->run('/help', [$this->module->id]);
	}

	public function options($actionID)
	{
		return ArrayHelper::merge(parent::options($actionID), [
					'deleteSource', 'outputFormat'
		]);
	}

	/**
	 * Нужно, чтобы в хелпе показывался правильный маршрут команд
	 *
	 * @inheritdoc
	 */
	public function getUniqueID()
	{
		return $this->id;
	}

	protected function output($string)
    {
        $args = func_get_args();
        $string .= PHP_EOL;
        array_shift($args);
        $string = Console::ansiFormat($string, $args);

        return Console::stdout($string);
    }

    protected function error($message)
    {
        $this->output($message, Console::FG_RED);
    }

	/**
	 * Функция расширенного журналирования действий контроллера.
	 * Все сообщения дублируются в системный журнал, а ошибки отображаются на
	 * консоли красным цветом, привлекая внимание.
	 * @param string $message Сообщение
	 * @param boolean $error Ошибка (true) / Обычное сообщение (false)
	 * @param type $func Функция, вызвавшая log
	 * @param type $prefix Префикс для формирования сообщения
	 */
	protected function log($message, $error = false, $func = '', $prefix = '')
	{
		$logLevel = $error ? LOG_ERR : LOG_INFO;
		$logColor = $error ? Console::FG_RED : null;
		$prefix = ($error ? 'Error:' : '') . $prefix;

		// Журналирование ошибки в системный журнал
		$msg = (!empty($prefix) ? $prefix . ' ' : '' ) . $message;
		syslog($logLevel, $msg);

		// Все сообщения выводятся на консоль
		$msg = (!empty($func) ? '[' . $func . '] ' : '') . $msg . "\n";
		$this->stdout($msg, $logColor);
	}
}