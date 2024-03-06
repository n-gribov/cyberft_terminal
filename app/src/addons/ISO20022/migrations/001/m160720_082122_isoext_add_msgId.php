<?php

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m160720_082122_isoext_add_msgId extends Migration
{
    public function up()
    {
        $table = Yii::$app->db->schema->getTableSchema('documentExtISO20022');
        if (!isset($table->columns['msgId'])) {
            $this->execute("alter table `documentExtISO20022` add column `msgId` varchar(64) not null default ''");
        }

        $documents = Document::findAll([
            'direction' => Document::DIRECTION_OUT,
            'typeGroup' => 'ISO20022'
        ]);

        foreach($documents as $model) {
            $storedFile = Yii::$app->storage->get($model->actualStoredFileId);
            if (!$storedFile || !is_readable($storedFile->getRealPath())) {
                continue;
            }

            $cyxDoc = CyberXmlDocument::read($model->actualStoredFileId);
            $typeModel = $cyxDoc->getContent()->getTypeModel();

            echo $typeModel->msgId . "\n";

            try {
                $model->extModel->msgId = $typeModel->msgId;

                $model->extModel->save(false);
            } catch (Exception $ex) {
                echo 'document id ' . $model->id . ': ' . $ex->getMessage() . "\n";
            }
        }
    }

    public function down()
    {
        $this->execute("alter table `documentExtISO20022` drop column `msgId`");

        return true;
    }

}
