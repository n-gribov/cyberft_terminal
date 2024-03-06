<?php

namespace common\commands\UserSetKeyAuth;

use common\commands\BaseCommand;

/**
 * User set key auth type command class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property integer $applayUser Applay user ID
 */
class UserSetKeyAuthCommand extends BaseCommand
{
    /**
     * @var integer $applyUser Apply user ID
     */
    public $applyUser;

    /**
     * @inheritdoc
     */
    public function getAcceptsCount()
    {
        if (is_null($this->_acceptsCount)) {
            $this->defineAcceptCount();
        }

        return $this->_acceptsCount;
    }

    /**
     * Define accept count
     */
    protected function defineAcceptCount()
    {
        $this->_acceptsCount = 1;
    }
}