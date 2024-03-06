<?php
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\swiftfin\models\documents\mt\Mt103Document;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m190226_085838_edm_ext_add_beneficiary extends Migration
{
    public function up()
    {
        $table = Yii::$app->db->schema->getTableSchema('documentExtEdmForeignCurrencyOperation');
        if (!isset($table->columns['beneficiary'])) {
            $this->execute("alter table `documentExtEdmForeignCurrencyOperation` add column `beneficiary` varchar(255) default NULL");
        }

        $documents = Document::find()->select('id, actualStoredFileId')
            ->where(
                [
                    'type' => [
                        'MT103',
                        VTBPayDocCurType::TYPE,
                        VTBRegisterCurType::TYPE,
                    ],
                ]
            )->asArray()->all();

        foreach ($documents as $item){
            $beneficiar = null;
            try {
                $typeModel = CyberXmlDocument::getTypeModel($item['actualStoredFileId']);
                if (!$typeModel) {
                    echo "The record with ID ".$item['id']." has no file.\r\n";
                    continue;
                }
                if ($typeModel->type == 'MT103') {
                    $document = new Mt103Document();
                    $data = $document->parseMtString($typeModel->source->content);
                    $beneficiar = substr($data[59], 1);
                } else if ($typeModel->type === 'VTBPayDocCur') {
                    $beneficiar = $typeModel->document->BENEFICIAR;
                }
            } catch (Exception $ex) {
                echo "Could not get beneficiar from a document: $ex";
            }

            $this->execute(
                'update `documentExtEdmForeignCurrencyOperation` set date = date, beneficiary = :ben where documentId = :itemId',
                [':ben' => $beneficiar, ':itemId' => $item['id']]
            );
        }

    }

    public function down()
    {
        $this->execute("alter table `documentExtEdmForeignCurrencyOperation` drop column `beneficiary`");

        return true;
    }
}
