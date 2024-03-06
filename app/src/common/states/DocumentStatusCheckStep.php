<?php

namespace common\states;

class DocumentStatusCheckStep extends BaseDocumentStep
{
    private $_targetStatus;

    public function __construct($targetStatus)
    {
        $this->_targetStatus = $targetStatus;
    }

    public function run()
    {
        return $this->_targetStatus === $this->state->document->status;
    }

}
