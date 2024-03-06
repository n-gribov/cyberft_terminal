<?php

namespace addons\edm\services\VTBDocumentCancellationService;

use common\document\Document;

class CancellationStatus
{
    const REJECTED = 'rejected';
    const PENDING = 'pending';
    const PROCESSED = 'processed';
    const SIGNATURE_REQUIRED = 'signatureRequired';

    private $status;

    /**
     * @var Document|null
     */
    private $cancellationRequestDocument;

    public function __construct(string $status, $cancellationRequestDocument = null)
    {
        $this->status = $status;
        $this->cancellationRequestDocument = $cancellationRequestDocument;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCancellationRequestDocument()
    {
        return $this->cancellationRequestDocument;
    }
}
