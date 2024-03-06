<?php

namespace common\modules\transport\components\stomp;

use Yii;
use yii\base\ErrorException;
use common\modules\transport\components\stomp\Frame;
use common\modules\transport\components\stomp\Exception\StompException;
use common\modules\transport\components\stomp\Message\Map;

/**
 * Copyright 2005-2006 The Apache Software Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/* vim: set expandtab tabstop=3 shiftwidth=3: */

/**
 * A Stomp Connection
 *
 *
 * @package Stomp
 * @author Hiram Chirino <hiram@hiramchirino.com>
 * @author Dejan Bosanac <dejan@nighttale.net>
 * @author Michael Caplan <mcaplan@labnet.net>
 * @version $Revision: 43 $
 */
class Stomp
{
    /**
     * Perform request synchronously
     *
     * @var boolean
     */
    public $sync = false;

    /**
     * Default prefetch size
     *
     * @var int
     */
    public $prefetchSize = 1;

    /**
     * Client id used for durable subscriptions
     *
     * @var string
     */
    public $clientId = null;
    protected $_brokerUri = null;
    protected $_socket = null;
    protected $_hosts = [];
    protected $_params = [];
    protected $_subscriptions = [];
    protected $_defaultPort = 61613;
    protected $_currentHost = -1;
    protected $_attempts = 3;
    protected $_username = '';
    protected $_password = '';
    protected $_sessionId;
    protected $_read_timeout_seconds = 5;
    protected $_read_timeout_milliseconds = 0;
    protected $_poll_timeout_seconds = 1;
    protected $_poll_timeout_milliseconds = 0;
    protected $_connect_timeout_seconds = 10;
    protected $_waitbuf = [];

    /**
     * @var integer Максимальная длина строки, помещаемой в трейслог
     */
    public static $traceLineLength;

    /**
     * @var boolean Флаг, управляющий выдачей сообщений в трейслог
     */
    public static $debug;

    /**
     * Constructor
     *
     * @param string $brokerUri Broker URL
     * @throws StompException
     */
    public function __construct($brokerUri)
    {
        $this->_brokerUri = $brokerUri;
        $this->init();
    }

    /**
     * Initialize connection
     *
     * @throws StompException
     */
    protected function init()
    {
        if (is_null(static::$debug)) {
            static::$debug = false;
        }

        if (is_null(static::$traceLineLength)) {
            static::$traceLineLength = 1024 * 3;
        }

        $pattern = "|^(([a-zA-Z0-9]+)://)+\(*([a-zA-Z0-9\.:/i,-]+)\)*\??([a-zA-Z0-9=&]*)$|i";

        if (preg_match($pattern, $this->_brokerUri, $regs)) {
            $scheme = $regs[2];
            $hosts = $regs[3];
            $params = $regs[4];
            if ($scheme != 'failover') {
                $this->processUrl($this->_brokerUri);
            } else {
                $urls = explode(',', $hosts);
                foreach ($urls as $url) {
                    $this->processUrl($url);
                }
            }
            if ($params != null) {
                parse_str($params, $this->_params);
            }
        } else {
            throw new StompException("Bad Broker URL {$this->_brokerUri}");
        }
    }

    /**
     * Process broker URL
     *
     * @param string $url Broker URL
     * @throws StompException
     * @return boolean
     */
    protected function processUrl($url)
    {
        $parsed = parse_url($url);
        if ($parsed) {
            array_push($this->_hosts, array($parsed['host'], $parsed['port'], $parsed['scheme']));
        } else {
            throw new StompException('Bad Broker URL ' . $url);
        }
    }

    /**
     * Make socket connection to the server
     *
     * @throws StompException
     */
    protected function makeConnection()
    {
        if (count($this->_hosts) == 0) {
            throw new StompException('No broker defined');
        }

        // force disconnect, if previous established connection exists
        $this->disconnect();

        $i = $this->_currentHost;
        $att = 0;
        $connected = false;
        $connect_errno = null;
        $connect_errstr = null;

        while (!$connected && $att++ < $this->_attempts) {
            if (isset($this->_params['randomize']) && $this->_params['randomize'] == 'true') {
                $i = rand(0, count($this->_hosts) - 1);
            } else {
                $i = ($i + 1) % count($this->_hosts);
            }
            $broker = $this->_hosts[$i];
            $host = $broker[0];
            $port = $broker[1];
            $scheme = $broker[2];
            if ($port == null) {
                $port = $this->_defaultPort;
            }
            if ($this->_socket != null) {
                fclose($this->_socket);
                $this->_socket = null;
            }

            $this->_socket = fsockopen(
                    $scheme . '://' . $host, $port, $connect_errno,
                    $connect_errstr, $this->_connect_timeout_seconds
            );

            if (!is_resource($this->_socket) && $att >= $this->_attempts && !array_key_exists($i + 1, $this->_hosts)) {
                throw new StompException("Could not connect to $host:$port ($att/{$this->_attempts})");
            } else if (is_resource($this->_socket)) {
                stream_set_blocking($this->_socket, false);
                socket_set_timeout($this->_socket, $this->_read_timeout_seconds, $this->_read_timeout_milliseconds);
                $connected = true;
                $this->_currentHost = $i;

                break;
            }
        }

        if (!$connected) {
            throw new StompException('Could not connect to a broker');
        }
    }

    /**
     * Connect to server
     *
     * @param string $username
     * @param string $password
     * @return boolean
     * @throws StompException
     */
    public function connect($username = '', $password = '')
    {
        $this->makeConnection();

        if ($username != '') {
            $this->_username = $username;
        }

        if ($password != '') {
            $this->_password = $password;
        }

        $headers = array('login' => $this->_username, 'passcode' => $this->_password);

        if ($this->clientId != null) {
            $headers['client-id'] = $this->clientId;
        }

        $frame = new Frame(Frame::CONNECT, $headers);
        $this->writeFrame($frame);

        $frame = $this->readFrame();
        if ($frame instanceof Frame && $frame->command == Frame::CONNECTED) {
            $this->_sessionId = $frame->headers['session'];

            return true;
        } else {
            if ($frame instanceof Frame) {
                // must be error command

                return false;
                //throw new StompException('Unexpected command: ' . $frame->command, 0, $frame->body);
            } else {
                throw new StompException('Connection not acknowledged');
            }
        }
    }

    /**
     * Check if client session has ben established
     *
     * @return boolean
     */
    public function isConnected()
    {
        return !empty($this->_sessionId) && is_resource($this->_socket);
    }

    /**
     * Current stomp session ID
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->_sessionId;
    }

    /**
     * Send a message to a destination in the messaging system
     *
     * @param string $destination Destination queue
     * @param string|Frame $msg Message
     * @param array $properties
     * @param boolean $sync Perform request synchronously
     * @return boolean
     */
    public function send($destination, $msg, $properties = array(), $sync = null)
    {
        if ($msg instanceof Frame) {
            $msg->headers['destination'] = $destination;
            if (is_array($properties)) {
                $msg->headers = array_merge($msg->headers, $properties);
            }
            $frame = $msg;
        } else {
            $headers = $properties;
            $headers['destination'] = $destination;
            $frame = new Frame(Frame::SEND, $headers, $msg);
        }
        $this->prepareReceipt($frame, $sync);
        $this->writeFrame($frame);

        return $this->waitForReceipt($frame, $sync);
    }

    /**
     * Prepair frame receipt
     *
     * @param Frame $frame
     * @param boolean $sync
     */
    protected function prepareReceipt(Frame $frame, $sync = null)
    {
        if ($sync === true || (is_null($sync) && $this->sync)) {
            $frame->headers['receipt'] = md5(microtime());
        }
    }

    /**
     * Wait for receipt
     *
     * @param Frame $frame
     * @param boolean $sync
     * @return boolean
     * @throws StompException
     */
    protected function waitForReceipt(Frame $frame, $sync = null)
    {
        if ($sync === true || (is_null($sync) && $this->sync)) {
            $id = (isset($frame->headers['receipt'])) ? $frame->headers['receipt'] : null;
            if ($id == null) {
                return true;
            }

            $buf = [];

            while (true) {
                $frame = $this->readFrame();
                if (!$frame) {
                    if (!empty($buf)) {
                        $this->_waitbuf = $buf;

                        return false;
                    }
                }
                if ($frame instanceof Frame && $frame->command == Frame::RECEIPT) {
                    if ($frame->headers['receipt-id'] == $id && $frame->body == 'OK') {
                        $this->_waitbuf = $buf;

                        return true;
                    } else {
                        throw new StompException('Unexpected receipt id '
                                        . $frame->headers['receipt-id'], 0, $frame->body);
                    }
                } else {
                    $buf[] = $frame;
                }
            }
        }

        return true;
    }

    /**
     * Register to listen to a given destination
     *
     * @param string $destination Destination queue
     * @param array $properties
     * @param boolean $sync Perform request synchronously
     * @return boolean
     * @throws StompException
     */
    public function subscribe($destination, $properties = null, $sync = null)
    {
        $headers = array('ack' => 'client');
        $headers['activemq.prefetchSize'] = $this->prefetchSize;
        if ($this->clientId != null) {
            $headers['activemq.subcriptionName'] = $this->clientId;
        }
        if (isset($properties)) {
            foreach ($properties as $name => $value) {
                $headers[$name] = $value;
            }
        }
        $headers['destination'] = $destination;
        $frame = new Frame(Frame::SUBSCRIBE, $headers);
        $this->prepareReceipt($frame, $sync);

        $this->writeFrame($frame);

        if ($this->waitForReceipt($frame, $sync) == true) {
            $this->_subscriptions[$destination] = $properties;

            return true;
        }

        return false;
    }

    /**
     * Remove an existing subscription
     *
     * @param string $destination
     * @param array $properties
     * @param boolean $sync Perform request synchronously
     * @return boolean
     * @throws StompException
     */
    public function unsubscribe($destination, $properties = null, $sync = null) {
        $headers = array();
        if (isset($properties)) {
            foreach ($properties as $name => $value) {
                $headers[$name] = $value;
            }
        }
        $headers['destination'] = $destination;
        $frame = new Frame(FRAME::UNSUBSCRIBE, $headers);
        $this->prepareReceipt($frame, $sync);
        $this->writeFrame($frame);
        if ($this->waitForReceipt($frame, $sync) == true) {
            unset($this->_subscriptions[$destination]);

            return true;
        }

        return false;
    }

    /**
     * Start a transaction
     *
     * @param string $transactionId
     * @param boolean $sync Perform request synchronously
     * @return boolean
     * @throws StompException
     */
    public function begin($transactionId = null, $sync = null)
    {
        $headers = array();
        if (isset($transactionId)) {
            $headers['transaction'] = $transactionId;
        }
        $frame = new Frame(Frame::BEGIN, $headers);
        $this->prepareReceipt($frame, $sync);
        $this->writeFrame($frame);

        return $this->waitForReceipt($frame, $sync);
    }

    /**
     * Commit a transaction in progress
     *
     * @param string $transactionId
     * @param boolean $sync Perform request synchronously
     * @return boolean
     * @throws StompException
     */
    public function commit($transactionId = null, $sync = null)
    {
        $headers = array();
        if (isset($transactionId)) {
            $headers['transaction'] = $transactionId;
        }
        $frame = new Frame(Frame::COMMIT, $headers);
        $this->prepareReceipt($frame, $sync);
        $this->writeFrame($frame);

        return $this->waitForReceipt($frame, $sync);
    }

    /**
     * Roll back a transaction in progress
     *
     * @param string $transactionId
     * @param boolean $sync Perform request synchronously
     */
    public function abort($transactionId = null, $sync = null)
    {
        $headers = array();
        if (isset($transactionId)) {
            $headers['transaction'] = $transactionId;
        }
        $frame = new Frame(Frame::ABORT, $headers);
        $this->prepareReceipt($frame, $sync);
        $this->writeFrame($frame);

        return $this->waitForReceipt($frame, $sync);
    }

    /**
     * Acknowledge consumption of a message from a subscription
     * Note: This operation is always asynchronous
     *
     * @param string|Frame $messageId Message ID
     * @param string $transactionId
     * @return boolean
     * @throws StompException
     */
    public function ack($messageId, $transactionId = null)
    {
        if ($messageId instanceof Frame) {
            $headers = $messageId->headers;
        } else {
            $headers = ['message-id' => $messageId];
        }

        if (isset($transactionId)) {
            $headers['transaction'] = $transactionId;
        }

        $frame = new Frame(Frame::ACK, $headers);

        return boolval($this->writeFrame($frame));
    }

    /**
     * Graceful disconnect from the server
     *
     */
    public function disconnect()
    {
        $headers = array();

        if ($this->clientId != null) {
            $headers['client-id'] = $this->clientId;
        }

        if (is_resource($this->_socket)) {
            $this->writeFrame(new Frame(Frame::DISCONNECT, $headers));
            fclose($this->_socket);
        }

        $this->_socket = null;
        $this->_sessionId = null;
        $this->_currentHost = -1;
        $this->_subscriptions = [];
        $this->_username = '';
        $this->_password = '';
        $this->_waitbuf = [];
    }

    /**
     * Write frame to server
     *
     * @param Frame $stompFrame
     */
    protected function writeFrame(Frame $stompFrame)
    {
        $command = $stompFrame->command;

        if (!is_resource($this->_socket)) {
            throw new StompException('ERROR writing frame ' . $command . ': connection is not a resource');
        }

        $data = $stompFrame->__toString();
        $bytesToWrite = strlen($data);
        $bytesWritten = 0;

        $chunks = 0;
        while ($bytesWritten < $bytesToWrite) {
            try {
                if ($bytesWritten == 0) {
                    $written = fwrite($this->_socket, $data, strlen($data));
                } else {
                    $written = fwrite($this->_socket, substr($data, $bytesWritten));
                }
            } catch (ErrorException $ex) {
                $this->_socket = null;
                $msg = 'ERROR writing frame ' . $command . ' bytes ' . $bytesWritten . '/' . $bytesToWrite
                        . "\n" . $ex->getMessage();

                throw new StompException($msg);
            }
            if ($written === false || $written == 0) {
                return ($bytesWritten == 0 ? false : $bytesWritten);
            }
            $chunks++;
            $bytesWritten += $written;
        }

        return $bytesWritten;
    }

    /**
     * Set timeout to wait for content to read
     *
     * @param int $seconds Seconds to wait for a frame
     * @param int $milliseconds Milliseconds to wait for a frame
     */
    public function setReadTimeout($seconds, $milliseconds = 0)
    {
        $this->_read_timeout_seconds = $seconds;
        $this->_read_timeout_milliseconds = $milliseconds;
    }

    /**
     * Read response frame from server
     *
     * @return Frame False when no frame to read
     */
    public function readFrame($checkFrameReady = true)
    {
        if (!empty($this->_waitbuf)) {
            return array_shift($this->_waitbuf);
        }

        if ($checkFrameReady) {
            if (!$this->hasFrameToRead()) {
                return false;
            }
        }

        $data = '';
        $stream_meta_data = stream_get_meta_data($this->_socket);

        try {
            if ($this->_socket) {
                while (!feof($this->_socket) && !$stream_meta_data['timed_out']) {
                    $chunk = fread($this->_socket, 4096);
                    //$chunk = fgets($this->_socket);
                    //echo "chunk: " . $chunk . "\n";

                    $data .= $chunk;

                    if (!empty($chunk) && $chunk{strlen($chunk) - 1} === "\x00") {
                        break;
                    }

                    $stream_meta_data = stream_get_meta_data($this->_socket);
                }

                $data = rtrim($data, "\n\x00");
                static::trace($data, 'Socket data');
            } else {
                throw new StompException('Frame read failed');
            }
        } catch (ErrorException $ex) {
            $this->_socket = null;

            throw new StompException($ex->getMessage(), $ex->getCode());
        }

        if ($stream_meta_data['timed_out']) {
            throw new StompException('Read timed out');
        }

        if (strlen($data) <= 0) {
            throw new StompException('Malformed empty frame');
        }

        $frameData = explode("\n\n", $data, 2);

        if (isset($frameData[0])) {
            $header = $frameData[0];
        } else {
            static::trace('Error: Frame has empty HEADER block');
            $header = null;
        }

        if (isset($frameData[1])) {
            $body = $frameData[1];
        } else {
            $body = null;
        }

        $header = explode("\n", $header);
        $headers = array();
        $command = null;

        foreach ($header as $v) {
            if (isset($command)) {
                $headerData = explode(':', $v, 2);

                if (isset($headerData[0])) {
                    if (isset($headerData[1])) {
                        $value = $headerData[1];
                    } else {
                        $value = null;
                    }

                    $headers[$headerData[0]] = $value;
                }
            } else {
                $command = $v;
            }
        }

        $frame = new Frame($command, $headers, trim($body));

        if (isset($frame->headers['transformation']) && $frame->headers['transformation'] == 'jms-map-json') {
            return new Map($frame);
        }

        return $frame;
    }

    /**
     * Check if there is a frame to read
     * @param array $sockets
     * @return boolean
     */
    public function hasFrameToRead($sockets = null)
    {
        $read = $sockets ?: [$this->_socket];
        $write = null;
        $except = null;

        // Опрашиваем сокет, используя задержки на ожидание данных
        $hasData = stream_select($read, $write, $except,
                $this->_poll_timeout_seconds, $this->_poll_timeout_milliseconds);

        if ($hasData !== false) {
            $hasData = count($read);
        }

        // Если произошла ошибка при опрашивании сокета, то прерываем исполнение
        if ($hasData === false) {
            throw new StompException('Check failed to determine if the socket is readable');
        } else if ($hasData > 0) {
            // Данные появились в сокете
            return $read;
        } else {
            return false;
        }

        return false;
    }

    /**
     * Reconnects and renews subscriptions (if there were any)
     * Call this method when you detect connection problems
     */
    protected function reconnect()
    {
        $subscriptions = $this->_subscriptions;
        $this->connect($this->_username, $this->_password);

        foreach ($subscriptions as $dest => $properties) {
            $this->subscribe($dest, $properties);
        }
    }

    /**
     * Graceful object desruction
     *
     */
    public function __destruct()
    {
        try {
            $this->disconnect();
        } catch (StompException $ex) {
            static::trace('Exception in Stomp::__destruct(): ' . $ex->getMessage());
        }
    }

    public function getSocket()
    {
        return $this->_socket;
    }

    /**
     * @param mixed $message
     */
    public static function trace($message, $info = null)
    {
        if (!static::$debug) {
            return;
        }

        if ($message instanceof Frame) {
            $loggedMessage = (string) $message;
        } else {
            $loggedMessage = self::shorter($message);
        }

        if (!is_null($info)) {
            $loggedMessage = $info . '. ' . $loggedMessage;
        }

        Yii::info($loggedMessage, 'stomp');

        return;
    }

    /**
     *
     * @param mixed $data
     * @return string
     */
    protected static function shorter($data)
    {
        return substr((is_string($data) ? $data : print_r($data, true)), 0, static::$traceLineLength);
    }

}
