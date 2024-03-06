<?php

namespace common\states\in;

use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;

/**
 * Класс содержит методы для шага отправки Status Report
 */
class StatusReportStep extends BaseDocumentStep
{
    public $name = 'report';

    /**
     * Метод запускает шаг
     * @return bool
     */
    public function run()
    {
        if ($this->state->document->status == Document::STATUS_VERIFIED) {
            // Отправить Status Report об успешной приемке сообщения
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

        // Отправить Status Report об отказе в обработке
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
