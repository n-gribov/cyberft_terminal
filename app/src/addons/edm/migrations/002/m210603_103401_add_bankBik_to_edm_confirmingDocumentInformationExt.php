<?php

use common\document\Document;
use yii\db\Migration;
use yii\db\Query;

class m210603_103401_add_bankBik_to_edm_confirmingDocumentInformationExt extends Migration
{
    private const TABLE_NAME = '{{%edm_confirmingDocumentInformationExt}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'bankBik',
            $this->string()
        );
        $this->createIndex('bankBik', self::TABLE_NAME, 'bankBik');
        $this->fillBiks();
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'bankBik');
    }

    private function fillBiks(): void
    {
        $documentsBiks = (new Query())
            ->select(['ext.documentId', 'bank.bik'])
            ->from('edm_confirmingDocumentInformationExt ext')
            ->leftJoin('document', 'document.id = ext.documentId')
            ->leftJoin('edmDictBank bank', 'document.receiver = bank.terminalId')
            ->where(['document.direction' => Document::DIRECTION_OUT])
            ->all();
        foreach ($documentsBiks as $record) {
            list('documentId' => $id, 'bik' => $bik) = $record;
            if (empty($bik)) {
                continue;
            }
            $this->update(
                self::TABLE_NAME,
                ['bankBik' => $bik],
                ['documentId' => $id]
            );
        }
    }
}
