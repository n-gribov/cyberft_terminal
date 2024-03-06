<?php
namespace common\states;

use common\base\BaseDocumentState;

class BaseDocumentStep
{
    /**
     * @var BaseDocumentState $state
     */
    public $state;

    public $name;

    public function log($msg, $category = null)
    {
        $this->state->log($msg, $category);
    }

    public function logError($msg)
    {
        $this->state->logError($msg);
    }

    public function onSuccess()
    {

    }

    public function onFail()
    {

    }

    public function onRetry($retryParams = null)
    {

    }

    public function cleanup()
    {

    }

}