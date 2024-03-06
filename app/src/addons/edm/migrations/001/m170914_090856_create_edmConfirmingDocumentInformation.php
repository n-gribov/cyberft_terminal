<?php

use yii\db\Migration;

/**
 * @CYB-3830
 */
class m170914_090856_create_edmConfirmingDocumentInformation extends Migration
{
    public function up()
    {
        $this->createTable('edmConfirmingDocumentInformation', [
            'id' => $this->primaryKey(),
            'number' => $this->string(),
            'correctionNumber' => $this->string(),
            'organizationId' => $this->integer(),
            'date' => $this->string(),
            'contractPassport' => $this->string(),
            'person' => $this->string(),
            'contactNumber' => $this->string(),
            'terminalId' => $this->integer(),
            'documentId' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('edmConfirmingDocumentInformation');

        return true;
    }
}
