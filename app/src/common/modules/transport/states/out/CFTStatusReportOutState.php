<?php

namespace common\modules\transport\states\out;

use common\base\BaseDocumentState;
use common\states\out\ServiceOutState;

class CFTStatusReportOutState extends BaseDocumentState
{
    public $senderId;
    public $receiverId;
    public $refDocId;
    public $statusCode;
    public $errorCode;
    public $errorDescription;

    protected $_steps = [
        'prepare' => 'common\modules\transport\states\out\CFTStatusReportPrepareStep',
    ];

    public function decideState()
    {
        if (!empty($this->cyxDoc)) {
            return new ServiceOutState();
        }
    }

}