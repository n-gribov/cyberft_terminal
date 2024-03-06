<?php

namespace common\models\sbbolxml\response\ResponseType\OrgSettingsAType;

/**
 * Class representing OrgUPGLogsRequestsAType
 */
class OrgUPGLogsRequestsAType
{

    /**
     * @property \common\models\sbbolxml\response\OrgUPGLogsRequestType $orgUPGLogsRequest
     */
    private $orgUPGLogsRequest = null;

    /**
     * Gets as orgUPGLogsRequest
     *
     * @return \common\models\sbbolxml\response\OrgUPGLogsRequestType
     */
    public function getOrgUPGLogsRequest()
    {
        return $this->orgUPGLogsRequest;
    }

    /**
     * Sets a new orgUPGLogsRequest
     *
     * @param \common\models\sbbolxml\response\OrgUPGLogsRequestType $orgUPGLogsRequest
     * @return static
     */
    public function setOrgUPGLogsRequest(\common\models\sbbolxml\response\OrgUPGLogsRequestType $orgUPGLogsRequest)
    {
        $this->orgUPGLogsRequest = $orgUPGLogsRequest;
        return $this;
    }


}

