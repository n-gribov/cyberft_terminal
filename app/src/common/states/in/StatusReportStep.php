<?php

namespace common\states\in;

use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;

class StatusReportStep extends BaseDocumentStep
{
    public $name = 'report';

    public function run()
    {
        if ($this->state->document->status == Document::STATUS_VERIFIED) {
    		// Отправляем StatusReport об успешной приемке сообщения
            DocumentTransportHelper::statusReport(
            $this->state->document,
                [
                    'statusCode' => 'ACDC',
                    'errorCode' => '0',
                    'errorDescription' => ''
                ]
            );

            return true;
        }

        DocumentTransportHelper::statusReport(
            $this->state->document,
            [
                'statusCode' => 'RJCT',
                'errorCode' => '9999',
                'errorDescription' => 'Terminal error: Unable to verify signature(s)'
            ]
        );

        return false;
    }

}
