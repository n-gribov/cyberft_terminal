<?php

namespace common\commands;

use common\commands\BaseCommand;
use Yii;

/**
 * Base handler class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 */
abstract class BaseHandler
{
    /**
     * Perform command
     *
     * @param BaseCommand $command
     * @return array|boolean Return array or result or FALSE
     */
    abstract public function perform($command);

    /**
     * Log
     *
     * @param string $message Log message
     */
    protected function log($message)
    {
        Yii::warning($message);
    }
}