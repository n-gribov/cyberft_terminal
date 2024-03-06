<?php

use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestExt;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m190301_132836_add_document_columns_to_documentExtEdmVTBCancellationRequest extends Migration
{
    public function up()
    {
        $table = Yii::$app->db->schema->getTableSchema('documentExtEdmVTBCancellationRequest');
        if (!isset($table->columns['cancelDocumentNum'])) {
            $this->execute("alter table `documentExtEdmVTBCancellationRequest` add column `cancelDocumentNum` bigint(20) default NULL");
        }

        if (!isset($table->columns['cancelDocumentType'])) {
            $this->execute("alter table `documentExtEdmVTBCancellationRequest` add column `cancelDocumentType` tinyint default NULL");
        }

        if (!isset($table->columns['cancelDocumentDate'])) {
            $this->execute("alter table `documentExtEdmVTBCancellationRequest` add column `cancelDocumentDate` date default ' 0000-00-00'");
        }

        $documents = Document::find()->select('id, actualStoredFileId')
            ->where(
                [
                    'type' => VTBCancellationRequestType::TYPE
                ]
            )->asArray()->all();

        foreach ($documents as $item){
            if (!isset($item['actualStoredFileId'])){
                continue;
            }

            $cyxDoc = CyberXmlDocument::read($item['actualStoredFileId']);
			$content = $cyxDoc->getContent();
			if (!method_exists($content, 'getTypeModel')) {
				echo "*** doc id: " . $item['id'] . " content class: " . get_class($content) . " getTypeModel() does not exist\n";
				continue;
			}
            $typeModel = $content->getTypeModel();
            $bsDocument = $typeModel->document;

            $cancelDocumentNum = $bsDocument->CANCELDOCNUMBER;
            $cancelDocumentType = $bsDocument->CANCELDOCTYPEID;
            $cancelDocumentDate = $bsDocument->CANCELDOCDATE->format('Y-m-d');

            \Yii::info('----');
            \Yii::info('ID: '.$item['id']);
            \Yii::info('ACTUAL STORED FILE ID: '.$item['actualStoredFileId']);
            \Yii::info('DOCUMENT NUM: '.$cancelDocumentNum);
            \Yii::info('DOCUMENT TYPE: '.$cancelDocumentType);
            \Yii::info('DOCUMENT DATE: '.$cancelDocumentDate);


            VTBCancellationRequestExt::updateAll(['cancelDocumentNum' => $cancelDocumentNum], ['documentId' => $item['id']]);
            VTBCancellationRequestExt::updateAll(['cancelDocumentType' => $cancelDocumentType], ['documentId' => $item['id']]);
            VTBCancellationRequestExt::updateAll(['cancelDocumentDate' => $cancelDocumentDate], ['documentId' => $item['id']]);
        }
    }

    public function down()
    {
        $this->execute("alter table `documentExtEdmVTBCancellationRequest` "
                . "drop column `cancelDocumentNum`,"
                . "drop column `cancelDocumentType`,"
                . "drop column `cancelDocumentDate");
    }

}
