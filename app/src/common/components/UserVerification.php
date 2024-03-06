<?php
namespace common\components;

use common\base\BaseSettings;

/**
 * Реализация базовой сущности Settings, которая хранит настройки в Редисе.
 *
 * @author a.nikolaenko
 *
 * @property bool $enabled approval enabled or not
 * @property array $approverList list of approvers
 */
class UserVerification extends BaseSettings
{
    /**
     * @var bool $enabled
     */
    public $enabled = false;

    /**
     * @var array $approverList
     */
    public $approverList = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $this->_vars['enabled'] = $this->enabled;
        $this->_vars['approverList'] = $this->approverList;

        return parent::save();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    protected function isValid()
    {
        return isset($this->_vars['enabled']) && isset($this->_vars['approverList']);
    }

    /**
     * @inheritdoc
     */
    protected function updateVars()
    {
        if (isset($this->_vars['enabled'])) {
            $this->enabled = $this->_vars['enabled'];
        }

        if (isset($this->_vars['approverList']) && is_array($this->_vars['approverList'])) {
            $this->approverList = $this->_vars['approverList'];
        }

    }

}