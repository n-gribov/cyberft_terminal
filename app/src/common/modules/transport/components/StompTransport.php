<?php

namespace common\modules\transport\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use common\modules\transport\components\stomp\Stomp;
use common\modules\transport\components\stomp\Exception\StompException;

class StompTransport extends Component
{
	/**
	 * @var integer Максимальная длина строки, помещаемой в трейслог
	 */
	public $traceLineLength;

	/**
	 * @var boolean Флаг, управляющий выдачей сообщений в трейслог
	 */
	public $debug;

	/**
	 * @var resource
	 */
	private $connection = null;

    /**
     * @var array error messages
     */
    private $_errors;

	public function init()
	{
		if (is_null($this->debug)) {
			$this->debug = false;
		}
		if (is_null($this->traceLineLength)) {
			$this->traceLineLength = 3 * 1024;
		}
	}

	/**
	 * Функция отправки группы сообщений
	 * @param $msg
	 * @param $login
	 * @param $password
	 * @param $queue
	 * @param $headers
	 * @return bool
     * @throws StompException
	 */
	public function send($msg, $login, $password, $queue, $headers = [])
	{
		try {
			if ($this->ensureConnection($login, $password) === false) {
                $this->addError('Could not establish connection');

				return false;
			}

			// Отправляем сообщение, дополнительно передавая протокольные и прикладные заголовки
			$result  = $this->sendWithHeaders($queue, $msg, $headers);

			return (bool) $result;
		} catch(StompException $ex) {
			$this->saveError($ex);

            return false;
		} finally {
            $this->closeConnection();
        }
	}

	/**
	 * Вспомогательная функция отправки сообщения.
	 * Формирует набор дополнительных заголовков, сопровождающих сообщение.
	 * @param type $queue
	 * @param array $msg Вектор [['message']='...',['headers']=['...'=>'...']]
	 * @param type $headers
	 * @return boolean
     * @throws StompException
	 */
	protected function sendWithHeaders($queue, $msg, $headers = [])
	{
		if (is_array($msg)) {
			$message = $msg['message']; // Сообщение
			$headers = array_merge($headers, $msg['headers']); // Дополнительные заголовки
		} else {
			$message = $msg; // Передано только сообщение
		}

		$headers = array_merge($headers, ['receipt' => uniqid('message', true)]);

		return $this->connection->send($queue, $message, $headers, true);
	}

	/**
	 * @param $login
	 * @param $password
	 * @param $queue
	 * @param $maxFramesCount
	 * @return array|false
     * @throws StompException
	 */
	public function receive($login, $password, $queue, $maxFramesCount = 1)
    {
		try {
			if ($this->ensureConnection($login, $password) === false) {
				return false;
			}

			// Количество читаемых фреймов должно быть целым числом, большим 0
			$maxFramesCount = ((int) $maxFramesCount) <= 0 ? 1 : $maxFramesCount;
			$frames = [];

			$this->connection->subscribe($queue, null, true);

			for ($i = 0; $i < $maxFramesCount && $this->connection->hasFrameToRead(); $i++) {
				// Ack отсылается для всех промежуточных фреймов, но не для последнего
				$frame = $this->readFrame($i < $maxFramesCount - 1, false);

                if ($frame) {
                    $frames[] = $frame;
                }
			}

            return $frames;
		} catch(StompException $ex) {
			$this->saveError($ex);

            return false;
		} finally {
            $this->closeConnection();
        }
	}

    public function selectReadConnections($connection, $sockets)
    {
        return $connection->hasFrameToRead($sockets);
    }

	public function getConnection($login, $password)
    {
		$connection = new Stomp($this->getDsn());
        $connection->sync = true;

		//$connection->setReadTimeout(4, 0);

		$result = $connection->connect($login, $password);

		Stomp::trace('Login ' . ($result ? 'ok' : 'failed'));

		return $result ? $connection : false;
	}

	/**
	 * Функция читает 1 фрейм, передавая Ack по результату чтения.
	 * @param boolean $sendAck
     * @param boolean $checkFrameReady проверять ли наличие данных в соединении
     * @param Stomp $connection какое соединение читать (null = собственное)
	 * @throws StompException
	 */
	public function readFrame($sendAck = true, $checkFrameReady = true, $connection = null)
	{
		try {
            if (!$connection) {
                $connection = $this->connection;
            }

            $frame = $connection->readFrame($checkFrameReady);
            if ($frame && $sendAck) {
                /**
                 * В случае, если после чтения фрейма планируется дисконнект,
                 * ACK посылать не надо, т.к. для брокера это сигнал о готовности принять следующий фрейм.
                 *
                 * Для нормальной работы очереди чтения необходимо посылать ack именно с
                 * параметром message-id, иначе чтение происходит только для 1 фрейма, а
                 * затем - ожидание тайм-аута, вне зависимости от количества оставшихся в
                 * очереди фреймов.
                 */

                if (!isset($frame->headers['message-id'])) {
                    echo '*** BAD HEADERS: ' . var_export($frame->headers, true) . "\n";
                } else {
                    $connection->ack($frame, $frame->headers['message-id']);
                }
            }

            return $frame;
        } catch(StompException $ex) {
			$this->saveError($ex);

            return false;
		}
	}

	/**
	 * @throws Exception
	 * @throws StompException
	 */
	private function ensureConnection($login, $password)
    {
		if (!is_null($this->connection)) {
			return true;
		}

		Stomp::$debug = $this->debug;
		Stomp::$traceLineLength = $this->traceLineLength;

		Stomp::trace("Connecting to: {$this->getDsn()}");

		$this->connection = new Stomp($this->getDsn());
        $this->connection->sync = true;
		//$this->connection->setReadTimeout(1, 0);

        Stomp::trace("Logging in as: {$login}");

		$result = $this->connection->connect($login, $password);

		Stomp::trace('Login ' . ($result ? 'ok' : 'failed'));

		return $result;
	}

	/**
	 * @return mixed - true|false при наличии соединения и null при его отсутствии
	 */
	public function closeConnection($connection = null)
    {
        if ($connection) {
            $connection->disconnect();
        } else {
    		if (!is_null($this->connection)) {
        		$this->connection->disconnect();
            	$this->connection = null;
            }
        }
	}

	public function getDsn()
	{
		return Yii::$app->settings->get('app')->processing['dsn'];
	}

    /**
     * Returns a value indicating whether there is any error.
     * @return boolean whether there is any error.
     */
    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    /**
     * Returns the errors.
     */
    public function getErrors()
    {
        return $this->_errors === null ? [] : $this->_errors;
    }

    /**
     * Returns the first error.
     * @return string the error message. Null is returned if no error.
     */
    public function getFirstError()
    {
        return isset($this->_errors) ? reset($this->_errors) : null;
    }

    /**
     * Adds a new error.
     * @param string $error new error message
     */
    public function addError($error = '')
    {
        $this->_errors[] = $error;
    }

    /**
     * Removes errors.
     */
    public function clearErrors()
    {
        $this->_errors = [];
    }

	/**
	 * Функция для перехватчика ошибок.
	 * @param StompException $ex
	 */
	protected function saveError(StompException $ex)
	{
		if ($ex) {
			$message = $ex->getMessage() . ' (' . $ex->getCode() . ') ' . $ex->getDetails();
			$this->addError($message);
			Stomp::trace($message, 'Error');
		}
	}

    public function trace($msg)
    {
        Stomp::trace($msg);
    }

}
