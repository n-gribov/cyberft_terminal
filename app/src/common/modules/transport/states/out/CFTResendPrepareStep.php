<?php

namespace common\modules\transport\states\out;

use common\document\Document;
use common\helpers\Address;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\CFTResendType;
use common\states\BaseDocumentStep;
use Yii;

class CFTResendPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        $this->state->origin = Document::ORIGIN_WEB;

        $typeModel = new CFTResendType([
            'refDocId' => $this->state->refDocId,
            'refSenderId' => $this->state->refSenderId,
        ]);

        $cyxDoc = new CyberXmlDocument([
            'docId' => Uuid::generate(),
            'docDate' => date('c'),
            'senderId' => Address::fixSender($this->state->receiverId),
            'receiverId' => Yii::$app->terminals->getProcessingAddress(),
            'docType' => $typeModel->type
        ]);

        $cyxDoc->getContent()->loadFromTypeModel($typeModel);

        $this->state->typeModel = $typeModel;
        $this->state->cyxDoc = $cyxDoc;

        return true;
    }

}