<?php

use addons\edm\models\Statement\StatementCyberXmlContent;
use addons\edm\models\Statement\StatementType;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m170123_093242_edm_ext_currency extends Migration
{
    public function up()
    {
        $this->execute("alter table `documentExtEdmStatement` add column `currency` varchar(3) not null");

        $documents = Document::findAll([
            'type' => 'Statement',
        ]);

        foreach($documents as $document) {
            $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);

            if (!$cyxDoc) {
                continue;
            }

            $content = $cyxDoc->getContent();

            if (!$content instanceOf StatementCyberXmlContent) {
                continue;
            }

            $typeModel = $content->getTypeModel();

            if (!$typeModel || !($typeModel instanceof StatementType)) {
                continue;
            }

            $extModelRelation = $document->getExtModel();
            if (!$extModelRelation) {
                continue;
            }
            $extModel = $extModelRelation->one();
            if ($extModel) {
                $extModel->currency = $typeModel->currency;
                $extModel->save(false);
            }
         }
    }

    public function down()
    {
        $this->execute("alter table `documentExtEdmStatement` drop column `currency`");

        return true;
    }

}
