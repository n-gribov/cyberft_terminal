<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ\GetFreeBankDocGOZRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ\GetFreeBankDocGOZResponse;
use addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ\GetFreeBankDocGOZListRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ\GetFreeBankDocGOZListResponse;

class WSGetFreeBankDocsGOZ extends BaseService
{
    /**
     * @param GetFreeBankDocGOZRequest $request
     * @return GetFreeBankDocGOZResponse
     */
    public function getFreeBankDocGOZ(GetFreeBankDocGOZRequest $request)
    {
        return $this->execute('GetFreeBankDocGOZ', $request);
    }

    /**
     * @param GetFreeBankDocGOZListRequest $request
     * @return GetFreeBankDocGOZListResponse
     */
    public function getFreeBankDocGOZList(GetFreeBankDocGOZListRequest $request)
    {
        return $this->execute('GetFreeBankDocGOZList', $request);
    }

}
