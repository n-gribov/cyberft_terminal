<?php

use common\document\Document;
use common\document\SignData;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m211207_152700_document_separate_signData
 */
class m211207_152700_document_separate_signData extends Migration
{

    private $_tableName = '{{%document}}';

    public function up()
    {
        $this->execute("create table signData as select id documentId, signData data from document where signData is not null and signData <> ''");
        $this->execute("alter table signData add primary key(documentId)");

        $this->dropColumn($this->_tableName, 'signData');
    }

    public function down()
    {
        $this->addColumn($this->_tableName, 'signData', Schema::TYPE_TEXT." COMMENT 'Document data for signature'");

        $signData = SignData::find()->all();
        foreach($signData as $model) {
            Document::updateAll(['signData' => $model->data], ['id' => $model->documentId]);
        }

        $this->execute('drop table signData');

        return true;
    }

}
