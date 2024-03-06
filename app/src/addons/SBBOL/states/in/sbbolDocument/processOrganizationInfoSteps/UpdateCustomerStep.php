<?php

namespace addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps;

use addons\SBBOL\helpers\SBBOLCustomerHelper;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessOrganizationInfoState;

/**
 * @property ProcessOrganizationInfoState $state
 */
class UpdateCustomerStep extends BaseStep
{
    public function run()
    {
        $organizationInfo = $this->state->sbbolDocument;
        $customerId = $organizationInfo->getOrgData()->getOrgId();
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            $this->log("Customer $customerId is not found in database");

            return false;
        }

        return SBBOLCustomerHelper::saveCustomer(
            $organizationInfo,
            $customer->isHoldingHead,
            $customer->holdingHeadId,
            $customer->senderName
        );
    }
}
