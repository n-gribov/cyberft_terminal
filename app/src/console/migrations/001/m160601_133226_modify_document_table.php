<?php

use yii\db\Migration;

class m160601_133226_modify_document_table extends Migration
{
    public function up()
    {
        $this->addColumn('document','senderParticipantId',$this->string(16));
        $this->addColumn('document','receiverParticipantId',$this->string(16));
        $this->alterColumn('document','sender',$this->string(16));
        $this->alterColumn('document','receiver',$this->string(16));

        $connection = Yii::$app->getDb();
        //
        $command = $connection->createCommand('UPDATE document SET senderParticipantId = CONCAT(SUBSTRING(sender,1,8),SUBSTRING(sender,10)) WHERE CHAR_LENGTH(sender) = 12');
        $result = $command->query();
        //
        $command = $connection->createCommand('UPDATE document SET senderParticipantId = sender WHERE CHAR_LENGTH(sender) = 11');
        $result = $command->query();
        //
        $command = $connection->createCommand('UPDATE document SET receiverParticipantId = CONCAT(SUBSTRING(receiver,1,8),SUBSTRING(receiver,10)) WHERE CHAR_LENGTH(sender) = 12');
        $result = $command->query();
        //
        $command = $connection->createCommand('UPDATE document SET receiverParticipantId = receiver WHERE CHAR_LENGTH(sender) = 11');
        $result = $command->query();
    }

    public function down()
    {
        $this->dropColumn('document','senderParticipantId');
        $this->dropColumn('document','receiverParticipantId');
        $this->alterColumn('document','sender',$this->string(32));
        $this->alterColumn('document','receiver',$this->string(32));
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
