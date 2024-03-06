<?php

use common\document\Document;
use yii\db\Expression;
use yii\db\Migration;

class m170529_082230_statusreport_actualstoredfile extends Migration
{
    public function up()
    {
        Document::updateAll(
            [
                'actualStoredFileId' => new Expression('encryptedStoredFileId')
            ],
            [
                'typeGroup' => 'service',
                'actualStoredFileId' => null,
                'direction' => 'IN'
            ]
        );
    }

    public function down()
    {
        echo "m170529_082230_statusreport_actualstoredfile cannot be reverted.\n";

        return true;
    }

}
