<?php

namespace common\modules\transport\components\stomp;

use common\modules\transport\components\stomp\Exception\StompException;

/**
 *
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
 * Stomp Frames are messages that are sent and received on a stomp connection.
 *
 * @package Stomp
 */
class Frame
{
    const ERROR = 'ERROR';
    const CONNECT = 'CONNECT';
    const CONNECTED = 'CONNECTED';
    const SEND = 'SEND';
    const MESSAGE = 'MESSAGE';
    const RECEIPT = 'RECEIPT';
    const SUBSCRIBE = 'SUBSCRIBE';
    const UNSUBSCRIBE = 'UNSUBSCRIBE';
    const BEGIN = 'BEGIN';
    const COMMIT = 'COMMIT';
    const ABORT = 'ABORT';
    const ACK = 'ACK';
    const NACK = 'NACK';
    const DISCONNECT = 'DISCONNECT';

    public $command;
    public $headers = [];
    public $body;

    /**
     * Constructor
     *
     * @param string $command
     * @param array $headers
     * @param string $body
     */
    public function __construct ($command = null, $headers = null, $body = null)
    {
        $this->_init($command, $headers, $body);
    }

    protected function _init ($command = null, $headers = null, $body = null)
    {
        $this->command = $command;
        if ($headers != null) {
            $this->headers = $headers;
        }
        $this->body = $body;

        if ($this->command == static::ERROR) {
//            $msg = 'Stomp Frame init: command = ' . static::ERROR;
//
//            if (isset($this->headers['message'])) {
//                $msg .= '; message: ' . $this->headers['message'];
//            }
//
//			throw new StompException($msg, 0, $this->body);
        }
    }

    /**
     * Convert frame to transportable string
     *
     * @return string
     */
    public function __toString()
    {
        $data = $this->command . "\n";

        foreach ($this->headers as $name => $value) {
            $data .= $name . ":" . $value . "\n";
        }

        $data .= "\n";
        $data .= $this->body;

        return $data .= "\x00";
    }

}