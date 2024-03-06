<?php
namespace common\base;

class Daemon extends \yii\base\BaseObject
{
	const DAEMON_NAME = 'defaultDaemon';

	const LOG_LEVEL_INFO = 1;
	const LOG_LEVEL_ERROR = 2;

	public $interval = 5;

	// Когда установится в true, демон завершит работу
    protected $stopServer = false;

	protected $pid;

	public function init()
	{
        $this->log('Сonstructed daemon');

		$this->pid = getmypid();

		// Ждем сигналы SIGTERM и SIGCHLD
        pcntl_signal(SIGTERM, array($this, 'childSignalHandler'));
        pcntl_signal(SIGCHLD, array($this, 'childSignalHandler'));
	}

	public function run()
	{
        $this->log('Running daemon pid: ' . $this->pid);

		// Пока $stop_server не установится в true, гоняем бесконечный цикл
		while (!$this->stopServer) {
			$this->beforePerform();
			$this->perform();
			$this->afterPerform();
			sleep($this->interval);
		}
	}

	public function childSignalHandler($signo, $pid = null, $status = null)
	{
		switch ($signo) {
			case SIGTERM:
				// При получении сигнала завершения работы устанавливаем флаг
				$this->stopServer = true;
				break;
			case SIGCHLD:
				// При получении сигнала от дочернего процесса
				if (!$pid) {
					$pid = pcntl_waitpid(-1, $status, WNOHANG);
				}
				// Пока есть завершенные дочерние процессы
				while ($pid > 0) {
					$pid = pcntl_waitpid(-1, $status, WNOHANG);
				}
				break;
			default:
			// все остальные сигналы
		}
	}

	protected function beforeTerminate()
	{
		$this->log('Terminating  ' . static::DAEMON_NAME . ' pid:' . $this->pid);
	}

	protected function log($message, $level = self::LOG_LEVEL_INFO)
    {
		echo date('[Y-m-d H:i:s] ') . $message . PHP_EOL;
	}

    protected function beforePerform()
    {
    }

    protected function perform()
    {
    }

    protected function afterPerform()
    {
    }

}