<?php

namespace common\commands\DocumentStatusUpdate;

use common\commands\BaseCommand;

/**
 * Document status update command class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property string  $status     New status
 * @preperty string  $info       Info message
 */
class DocumentStatusUpdateCommand extends BaseCommand
{
    /**
     * @var string $status New status
     */
    public $status;

    /**
     * @var string $info Info message
     */
    public $info;

    /**
     * @inheritdoc
     */
    public function getAcceptsCount()
    {
        if (empty($this->_acceptsCount)){
            $this->defineAcceptCount();
        }

        return $this->_acceptsCount;
    }

    /**
     * Define accept count
     */
    protected function defineAcceptCount()
    {
        $this->_acceptsCount = 0;
    }
}
