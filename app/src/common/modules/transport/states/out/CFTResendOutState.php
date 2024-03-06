<?php

namespace common\modules\transport\states\out;

use common\base\BaseDocumentState;
use common\states\out\ServiceOutState;

class CFTResendOutState extends BaseDocumentState
{
    public $refDocId;
    public $refSenderId;
    public $receiverId;

    protected $_steps = [
        'prepare' => 'common\modules\transport\states\out\CFTResendPrepareStep',
    ];

    public function decideState()
    {
        if (!empty($this->cyxDoc)) {
            return new ServiceOutState();
        }
    }

}