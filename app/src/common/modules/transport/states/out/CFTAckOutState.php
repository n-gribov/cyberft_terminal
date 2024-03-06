<?php

namespace common\modules\transport\states\out;

use common\base\BaseDocumentState;
use common\states\out\ServiceOutState;

class CFTAckOutState extends BaseDocumentState
{
    public $receiverId;
    public $refDocId;
    public $refSenderId;

    protected $_steps = [
        'prepare' => 'common\modules\transport\states\out\CFTAckPrepareStep',
    ];

    public function decideState()
    {
        if (!empty($this->cyxDoc)) {
            return new ServiceOutState();
        }
    }

}