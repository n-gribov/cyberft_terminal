<?php

use yii\db\Migration;

class m200120_134823_add_document_format_group_to_participant_BICDir extends Migration
{
    private $tableName = '{{%participant_BICDir}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'documentFormatGroup', $this->string());
        $this->fillDocumentFormatGroup();
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'documentFormatGroup');
    }

    private function fillDocumentFormatGroup()
    {
        $participants = (new \yii\db\Query())
            ->select('id, participantBIC')
            ->from($this->tableName)
            ->all();

        foreach ($participants as $participant) {
            $documentFormatGroup = \common\modules\participant\models\BICDirParticipant::getDefaultDocumentFormatGroup($participant['participantBIC']);
            if ($documentFormatGroup) {
                $this->update(
                    $this->tableName,
                    ['documentFormatGroup' => $documentFormatGroup],
                    ['id' => $participant['id']]
                );
            }
        }
    }
}
