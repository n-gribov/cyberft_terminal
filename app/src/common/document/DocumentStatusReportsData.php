<?php

namespace common\document;

use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use common\base\BaseType;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\StatusReportType;

class DocumentStatusReportsData
{
    private $rejectionReason;
    private $statusDates = [];

    public function __construct(Document $document)
    {
        $statusReports = $this->findStatusReports($document);
        $this->extractStatusReportsData($statusReports);
    }

    public function getRejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function getStatusDate(string $statusCode): ?string
    {
        return isset($this->statusDates[$statusCode])
            ? $this->statusDates[$statusCode][0]
            : null;
    }

    private function findStatusReports(Document $document): array
    {
        return $document
            ->findReferencingDocuments()
            ->andWhere(['in', 'type', [StatusReportType::TYPE, PaymentStatusReportType::TYPE]])
            ->all();
    }

    /**
     * @param Document[] $statusReportDocuments
     */
    private function extractStatusReportsData(array $statusReportDocuments): void
    {
        foreach ($statusReportDocuments as $document) {
            /** @var CyberXmlDocument $cyxDocument */
            $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
            /** @var StatusReportType|PaymentStatusReportType **/
            $typeModel = $cyxDocument->getContent()->getTypeModel();

            $rejectionReason = $this->extractRejectionReason($typeModel);
            if ($rejectionReason) {
                $this->rejectionReason = $rejectionReason;
            }

            $this->statusDates[$typeModel->statusCode][] = $document->dateCreate;
        }
    }

    /**
     * @param StatusReportType|PaymentStatusReportType $typeModel
     * @return string|null
     */
    private function extractRejectionReason(BaseType $typeModel): ?string
    {
        if ($typeModel->statusCode !== 'RJCT') {
            return null;
        }

        if ($typeModel instanceof StatusReportType && $typeModel->errorDescription) {
            return $typeModel->errorDescription;
        }

        if ($typeModel instanceof PaymentStatusReportType && $typeModel->statusComment) {
            return $typeModel->statusComment;
        }

        return null;
    }
}
