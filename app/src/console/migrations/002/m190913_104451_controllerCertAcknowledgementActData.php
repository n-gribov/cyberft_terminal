<?php

use yii\db\Migration;

/**
 * Class m190913_104451_controllerCertAcknowledgementActData
 */
class m190913_104451_controllerCertAcknowledgementActData extends Migration
{
    private $tableName = '{{%controllerCertAcknowledgementActData}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'                             => $this->primaryKey(),
                'agreementType'                  => $this->string(),
                'agreementNumber'                => $this->string(),
                'agreementDate'                  => $this->date()->defaultValue(null),
                'signerFullName'                 => $this->string(),
                'signerPosition'                 => $this->string(),
                'signerAuthority'                => $this->string(),
                'certOwnerPosition'              => $this->string(),
                'certOwnerPassportCountry'       => $this->string(),
                'certOwnerPassportSeries'        => $this->string(),
                'certOwnerPassportNumber'        => $this->string(),
                'certOwnerPassportAuthorityCode' => $this->string(),
                'certOwnerPassportAuthority'     => $this->string(),
                'certOwnerPassportIssueDate'     => $this->date()->defaultValue(null),
                'controllerId'                   => $this->integer()->notNull()->unique(),
            ]
        );
        $this->addForeignKey(
            'fk_controllerCertAcknowledgementActData_controllerId',
            $this->tableName,
            'controllerId',
            '{{%controller}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
