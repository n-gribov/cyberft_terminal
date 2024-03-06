<?php

namespace addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps;

use addons\SBBOL\components\SBBOLTransport;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessOrganizationInfoState;
use common\helpers\Uuid;
use common\models\sbbolxml\request\PersonalInfoType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\SBBOLTransportConfig;

/**
 * @property ProcessOrganizationInfoState $state
 */
class ImportHoldingBranchesStep extends BaseStep
{
    public function run()
    {
        $organizationInfo = $this->state->sbbolDocument;
        $request = SBBOLRequest::findOne($this->state->requestId);

        foreach ($organizationInfo->getOrgData()->getHoldingOrgs() as $branchId) {
            $requestResult = $this->sendBranchPersonalInfoRequest(
                $request->customer,
                $branchId
            );
            if (!$requestResult->isSent()) {
                $this->log("Failed to send branch personal info request, error: {$requestResult->getErrorMessage()}");
            }
        }

        return true;
    }

    private function sendBranchPersonalInfoRequest(SBBOLCustomer $senderCustomer, $branchId): SBBOLTransport\SendAsyncResult
    {
        $this->log("Sending organization info request for branch organization $branchId");

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($branchId)
            ->setSender($senderCustomer->senderName)
            ->setPersonalInfo(new PersonalInfoType());

        $sessionId = $this->state->module->sessionManager->findOrCreateSession($senderCustomer->holdingHeadId);

        return $this->state->module->transport->sendAsync(
            $requestDocument,
            $sessionId,
            [
                'action' => 'importCustomer',
                'holdingHeadId' => $senderCustomer->holdingHeadId,
                'isHoldingHead' => false,
                'senderName' => $senderCustomer->senderName,
            ],
            null,
            null,
            $senderCustomer->holdingHeadId
        );
    }
}
