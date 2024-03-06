<?php
namespace common\commands\SwiftFinDocumentAuthorize;

use addons\swiftfin\models\SwiftFinDocumentExt;
use common\commands\BaseHandler;
use common\commands\SwiftFinDocumentAuthorize\SwiftFinDocumentAuthorizeCommand;
use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;

class SwiftFinDocumentAuthorizeHandler extends BaseHandler
{
    public function perform($command)
    {
        if ($command instanceof SwiftFinDocumentAuthorizeCommand) {
            $document = Document::findOne($command->entityId);

            if (is_null($document)) {
                $this->log('Wrong document ID');
                return false;
            }

            $extModel = SwiftFinDocumentExt::findOne(['documentId' => $document->id]);
            $status = $command->extStatus;

            $extModel->extStatus = $status;
            $extModel->save(false, ['extStatus']);

            if ($status == SwiftFinDocumentExt::STATUS_AUTHORIZED) {
                if ($document->isSignable()) {
                    $document->updateStatus(Document::STATUS_FORSIGNING);
                } else {
                    $document->updateStatus(Document::STATUS_ACCEPTED);
                    // Создать стейт отправки документа
                    DocumentTransportHelper::createSendingState($document);
                }
            }
        } else {
            $this->log('Command is not instance of SwiftFinDocumentAuthorizeCommand!');
            return false;
        }

        return [];
    }

}