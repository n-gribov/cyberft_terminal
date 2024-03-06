<?php

use addons\fileact\models\FileActDocumentExt;
use addons\fileact\models\FileActType;
use common\document\Document;
use yii\db\Migration;

class m171214_142203_extmodel_add_fields extends Migration
{
    public function up()
    {
        $this->execute('alter table `documentExtFileAct` add column `senderReference` varchar(64)');
        $this->execute('alter table `documentExtFileAct` add column `binFileName` varchar(255)');

        $documents = Document::findAll(['type' => 'fileact']);

        try {
            foreach($documents as $document) {
                $extModel = FileActDocumentExt::findOne(['documentId' => $document->id]);
                if (!$extModel) {
                    echo 'extModel not found for document ' . $document->id . "\n";

                    continue;
                }
                
                $pduStoredFile = Yii::$app->storage->get($extModel->pduStoredFileId);

                if (!$pduStoredFile) {
                    echo 'PDU stored file not found for document ' . $document->id . "\n";

                    continue;
                }

                $path = $pduStoredFile->getRealPath();
                $data = @file_get_contents($path);

                if (!$data) {
                    echo 'Could not read data for document ' . $document->id . ' from PDU file ' . $path . "\n";
                }

                $typeModel = new FileActType;
                $typeModel->loadHeader($path);

                $extModel->binFileName = $typeModel->binFileName;
                $senderReference = $typeModel->senderReference;
//                echo 'Document ' . $document->id . ' binFileName: ' . $extModel->binFileName
//                        . ' senderReference: ' . $senderReference . "\n";

                $extModelCount = FileActDocumentExt::find()->where(['senderReference' => $senderReference])->count();
                if (!$extModelCount) {
                    $extModel->senderReference = $senderReference;
                } else {
                    echo 'Skipped duplicate sender reference: ' . $senderReference . "\n";
                }

                if (!$extModel->save()) {
                    var_dump($extModel->errors);
                }
            }
        } catch(\Exception $ex) {
            echo $ex->getMessage() . "\n";
        }
    }

    public function down()
    {
        $this->execute('alter table `documentExtFileAct` drop column `senderReference`');
        $this->execute('alter table `documentExtFileAct` drop column `binFileName`');

        return true;
    }

}
