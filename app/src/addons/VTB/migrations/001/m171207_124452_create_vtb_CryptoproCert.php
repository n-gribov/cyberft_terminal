<?php

use yii\db\Migration;

class m171207_124452_create_vtb_CryptoproCert extends Migration
{
    private $tableName = '{{%vtb_CryptoproCert}}';
    private $terminalIdForeignKeyName = 'fk_vtb_cryptoproCert_terminalId_to_terminal_id';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'ownerName'    => $this->string(255),
            'certData'     => $this->text(),
            'status'       => $this->string(32),
            'keyId'        => $this->string(255),
            'serialNumber' => $this->string(255),
            'terminalId'   => $this->integer(),
            'validBefore'  => $this->dateTime()
        ]);
        $this->addForeignKey(
            $this->terminalIdForeignKeyName,
            $this->tableName,
            'terminalId',
            'terminal',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey($this->terminalIdForeignKeyName, $this->tableName);
        $this->dropTable($this->tableName);
    }
}
