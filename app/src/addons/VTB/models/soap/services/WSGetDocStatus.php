<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSGetDocStatus\GetDocStatusRequest;
use addons\VTB\models\soap\messages\WSGetDocStatus\GetDocStatusResponse;

class WSGetDocStatus extends BaseService
{
    /**
     * @param GetDocStatusRequest $request
     * @return GetDocStatusResponse
     */
    public function getDocStatus(GetDocStatusRequest $request)
    {
        return $this->execute('GetDocStatus', $request);
    }

}
