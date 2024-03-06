<?php

namespace console\controllers;

use yii\console\Controller;

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;


class DiagController extends Controller
{
    public function actionVerifyDocument($id)
    {
        $document = Document::findOne($id);

        $cyx = CyberXmlDocument::read($document->actualStoredFileId);

        var_dump($cyx->verify());

        echo "\n";
    }

    public function actionVerifyFile($file)
    {
        $cyx = new CyberXmlDocument();
        $cyx->loadXml(file_get_contents($file));

        var_dump($cyx->verify());

        echo "\n";
    }

    public function actionPushDocument($id)
    {
        $document = Document::findOne(['id' => $id, 'direction' => Document::DIRECTION_IN]);
        if (!$document) {
            echo "document $id not found\n";

            return;
        }
        if ($document->status != Document::STATUS_VERIFICATION_FAILED) {
            echo 'status ' . $document->status . " not supported\n";

            return;
        }

        $state = new \common\states\in\DocumentInState();
        $state->document = $document;
        $state->document->status = Document::STATUS_VERIFIED;
        $state->storedFileId = $document->actualStoredFileId;
        $state->status = 'statusReport';

        \common\states\StateRunner::run($state);

    }

}
