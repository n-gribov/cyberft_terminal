<?php
namespace common\base;

use Resque_Job;
use Yii;
use yii\helpers\Console;

/**
 * Description of Job
 *
 * @author fuzz
 */
abstract class Job
{
	const DEFAULT_QUEUE_NAME = 'default';
	/**
     * Максимальное время исполнения задачи (сек.)
     * @var integer
     */
    const MAX_RUNTIME = 60;
	/**
	 * Аргументы, переданные данной задаче
	 * @var type
	 */
	public $args;

	/**
	 * Полная информация о задаче
	 * @var Resque_Job
	 */
	public $job;
	/**
	 * Имя очереди, в которой размещено задание
	 * @var string
	 */
	public $queue;

	/**
	 * Идентификатор данной задачи для облегчения доступа
	 * @var string
	 */
	public $token;

	protected $_enableProfiling = true;

    protected $_logCategory = 'system';

	public function setUp()
	{
		if ($this->job instanceof Resque_Job) {
			$this->token = $this->job->payload['id'];
			if ($this->_enableProfiling === true && YII_DEBUG === true) {
//				Yii::$app->resque->startProfiling($this->token, get_called_class());
			}
		}
	}

	public function perform() {

    }

	/**
	 * Функция записывает в журнал информацию о задании
	 * @param string $message
	 */
    public function log($message, $warning = false, $category = false)
    {
        if (!$category) {
            $category = $this->_logCategory;
        }

        $loggedMessage = "[{$this->jobName()}] " . $message;

        if (!$warning) {
            Yii::info($loggedMessage, $category);
        } else {
            Yii::warning($loggedMessage, $category);
        }
    }

	public function jobName()
	{
		return basename(str_replace('\\', '/', get_called_class()));
	}

	protected function debug($string)
	{
		$string = Console::ansiFormat("\n" . $string . "\n",  [Console::FG_YELLOW]);

        return Console::stdout($string);
	}

//	public function tearDown()
//    {
//		if ($this->_enableProfiling === true && YII_DEBUG === true) {
//			Yii::$app->resque->endProfiling($this->token);
//		}
//	}

}