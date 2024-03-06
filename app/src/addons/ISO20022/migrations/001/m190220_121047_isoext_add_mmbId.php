<?php

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m190220_121047_isoext_add_mmbId extends Migration
{
    public function up()
    {
        $table = Yii::$app->db->schema->getTableSchema('documentExtISO20022');
        if (!isset($table->columns['mmbId'])) {
            $this->execute("alter table `documentExtISO20022` add column `mmbId` varchar(64) not null default ''");
        }

        $this->db->schema->refresh();
        $lastId = 0;

        do {
            $documents = Document::find()
                    ->where(['typeGroup' => 'ISO20022'])
                    ->andWhere(['>', 'id', $lastId])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(10000)
                    ->all();

            foreach ($documents as $model) {
                $lastId = $model->id;
                try {
                    $storedFile = Yii::$app->storage->get($model->actualStoredFileId);
                    if (!$storedFile || !is_readable($storedFile->getRealPath())) {
                        continue;
                    }

                    $cyxDoc = CyberXmlDocument::read($model->actualStoredFileId);
                    $contentModel = $cyxDoc->getContent();
                    $typeModel = $contentModel->getTypeModel();

                    switch ($typeModel->type) {
                        case 'auth.018':
                        case 'auth.024':
                        case 'auth.025':
                            $mmbId = $typeModel->mmbId;
                            break;
                        default:
                            $mmbId = '';
                            break;
                    }

                    echo 'document id=' . $model->id . ' mmbId=' . $mmbId . "\n";

                    $extModel = $model->extModel;
                    if ($extModel) {
                        $extModel->mmbId = $mmbId;
                        $extModel->save(false);
                    }
                } catch (\Throwable $ex) {
                    echo $ex->getMessage() . "\n";
                }
            }

        } while(count($documents));
    }

    public function down()
    {
        $this->execute("alter table `documentExtISO20022` drop column `mmbId`");

        return true;
    }
}
