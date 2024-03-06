<?php

use yii\db\Migration;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\models\cyberxml\CyberXmlDocument;

class m160920_141342_add_filename_column_to_documentExtISO20022_table extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtISO20022', 'fileName', 'string after descr');

        // Запись значения поля для существующих документов

        $extModel = ISO20022DocumentExt::find()->all();

        foreach ($extModel as $item) {

            $document = $item->document;

            if (!$document) {
                continue;
            }

            try {

                $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

                if (!$typeModel) {
                    continue;
                }

                $item->fileName = str_replace('attach_', '', $typeModel->fileName);
                $item->save();

            } catch (Exception $e) {
                continue;
            }

        }
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'fileName');
        return true;
    }
}
