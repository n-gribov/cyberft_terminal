<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocResponse;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocListRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocListResponse;

class WSGetFreeBankDocs extends BaseService
{
    /**
     * @param GetFreeBankDocRequest $request
     * @return GetFreeBankDocResponse
     */
    public function getFreeBankDoc(GetFreeBankDocRequest $request)
    {
        return $this->execute('GetFreeBankDoc', $request);
    }

    /**
     * @param GetFreeBankDocListRequest $request
     * @return GetFreeBankDocListResponse
     */
    public function getFreeBankDocList(GetFreeBankDocListRequest $request)
    {
        return $this->execute('GetFreeBankDocList', $request);
    }

}
