<?php

use yii\db\Exception;
use yii\db\Migration;

class m180301_143824_document_uuidRemote_index extends Migration
{
    public function up()
    {
        $command = Yii::$app->db->createCommand();

        try {
            $command->dropIndex('uuidRemote', 'document')->execute();
        } catch (Exception $ex) {
            echo "uuidRemote key does not exist\n";
        }

        $command->createIndex('uuidRemote', 'document', 'uuidRemote')->execute();
    }

    public function down()
    {
        $command = Yii::$app->db->createCommand();

        try {
            $command->dropIndex('uuidRemote', 'document')->execute();
        } catch (Exception $ex) {
            echo "uuidRemote key does not exist\n";
        }

        return true;
    }

}
