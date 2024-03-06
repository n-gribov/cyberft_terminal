<?php
namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\in\sbbolDocument\commonSteps\FinishProcessingStep;
use addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps\ImportCustomerStep;
use addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps\ImportHoldingBranchesStep;
use addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps\SaveNewPublishedCertificateStep;
use addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps\UpdateCustomerStep;
use common\models\sbbolxml\response\OrganizationInfoType;

/**
 * @property OrganizationInfoType $sbbolDocument
 */
class ProcessOrganizationInfoState extends BaseSBBOLSingleDocumentState
{
    protected $steps = [
        'decide' => null,
        'finishProcessing' => FinishProcessingStep::class,
    ];

    public function decideStep()
    {
        $request = SBBOLRequest::findOne($this->requestId);
        $action = $request->getResponseHandlerParam('action');

        switch ($action) {
            case 'saveNewPublishedCertificate':
                return SaveNewPublishedCertificateStep::class;
            case 'updateCustomer':
                return UpdateCustomerStep::class;
            case 'importCustomer':
                return ImportCustomerStep::class;
            case 'importHoldingBranches':
                return ImportHoldingBranchesStep::class;
        }

        return null;
    }
}
