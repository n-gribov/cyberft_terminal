<?php

namespace common\states\in;

use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;

class ServiceProcessStep extends BaseDocumentStep
{
    public $name = 'process';

    public function run()
    {
        $type = $this->state->document->type;
        $typeModel = $this->state->cyxDoc->getContent()->getTypeModel();

        if ('CFTStatusReport' === $type) {
            DocumentTransportHelper::processStatusReport($typeModel);
        } else if ('CFTAck' === $type) {
            DocumentTransportHelper::processAck($typeModel);
        } else if ('CFTChkAck' === $type) {
            DocumentTransportHelper::processChkAck($typeModel);
        } else if ('StatusReport' === $type) {
            DocumentTransportHelper::processBusinessStatusReport($typeModel);
        }

        return true;
    }

}
