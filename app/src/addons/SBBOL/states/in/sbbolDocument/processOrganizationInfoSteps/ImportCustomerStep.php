<?php

namespace addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps;

use addons\SBBOL\helpers\SBBOLCustomerHelper;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessOrganizationInfoState;

/**
 * @property ProcessOrganizationInfoState $state
 */
class ImportCustomerStep extends BaseStep
{
    public function run()
    {
        $organizationInfo = $this->state->sbbolDocument;
        $request = SBBOLRequest::findOne($this->state->requestId);

        return SBBOLCustomerHelper::saveCustomer(
            $organizationInfo,
            $request->getResponseHandlerParam('isHoldingHead'),
            $request->getResponseHandlerParam('holdingHeadId'),
            $request->getResponseHandlerParam('senderName')
        );
    }
}
