<?php

namespace common\commands\DocumentEdit;

use common\commands\BaseCommand;

/**
 * Document edit command command class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property string $typeModel Serialize document type model
 */
class DocumentEditCommand extends BaseCommand
{
    /**
     * @var string $typeModel Serialize document type model
     */
    public $typeModel;

    /**
     * @inheritdoc
     */
    public function getAcceptsCount()
    {
        if(empty($this->_acceptsCount)){
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