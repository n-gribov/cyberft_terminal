<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSPrepareDocForCancel\PrepareDocForCancelRequest;
use addons\VTB\models\soap\messages\WSPrepareDocForCancel\PrepareDocForCancelResponse;

class WSPrepareDocForCancel extends BaseService
{
    /**
     * @param PrepareDocForCancelRequest $request
     * @return PrepareDocForCancelResponse
     */
    public function prepareDocForCancel(PrepareDocForCancelRequest $request)
    {
        return $this->execute('PrepareDocForCancel', $request);
    }

}
