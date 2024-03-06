<?php

namespace common\modules\transport\states\out;

use common\document\Document;
use common\helpers\Address;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\CFTStatusReportType;
use common\states\BaseDocumentStep;
use Yii;

class CFTStatusReportPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        $this->state->origin = Document::ORIGIN_WEB;

        $typeModel = new CFTStatusReportType([
            'refDocId' => $this->state->refDocId,
            'statusCode' => $this->state->statusCode,
            'errorCode' => $this->state->errorCode,
            'errorDescription' => $this->state->errorDescription
        ]);

        // Создаем ответный StatusReport в виде CyberXmlDocument,
        // указываем значения для атрибутов заголовка
        $cyxDoc = new CyberXmlDocument([
            'docId' => Uuid::generate(),
            'docDate' => Yii::$app->formatter->asDatetime(gmdate('Y-m-d H:i:s'), 'php:c'),
            'senderId' => Address::fixSender($this->state->receiverId),
			'receiverId' => $this->state->senderId,
            'docType' => $typeModel->type
        ]);

        $cyxDoc->getContent()->loadFromTypeModel($typeModel);

        $this->state->typeModel = $typeModel;
        $this->state->cyxDoc = $cyxDoc;

        return true;
    }

}