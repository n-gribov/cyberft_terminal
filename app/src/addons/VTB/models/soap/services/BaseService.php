<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\BaseMessage;
use addons\VTB\VTBModule;

class BaseService
{
    /** @var VTBModule module **/
    protected $module;

    public function __construct()
    {
        $this->module = \Yii::$app->getModule('VTB');
    }

    protected function execute($requestName, BaseMessage $request)
    {
        return $this->module->transport->send(
            $this->getServiceName(),
            $requestName,
            $request
        );
    }

    protected function getServiceName()
    {
        // return class name without namespace
        return preg_replace('/.*?([^\\\\]+)$/', '$1', static::class);
    }
}
