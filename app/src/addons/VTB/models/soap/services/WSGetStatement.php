<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSGetStatement\GetStatementRequest;
use addons\VTB\models\soap\messages\WSGetStatement\GetStatementResponse;

class WSGetStatement extends BaseService
{
    /**
     * @param GetStatementRequest $request
     * @return GetStatementResponse
     */
    public function getStatement(GetStatementRequest $request)
    {
        return $this->execute('GetStatement', $request);
    }

}
