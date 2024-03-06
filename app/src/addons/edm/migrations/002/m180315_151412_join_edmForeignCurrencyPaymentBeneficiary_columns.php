<?php

use yii\db\Migration;

class m180315_151412_join_edmForeignCurrencyPaymentBeneficiary_columns extends Migration
{
    private $tableName = 'edmForeignCurrencyPaymentBeneficiary';

    public function up()
    {
        $this->addColumn($this->tableName, 'description', $this->string());

        $beneficiaries = Yii::$app->db->createCommand('select * from edmForeignCurrencyPaymentBeneficiary')->queryAll();
        foreach ($beneficiaries as $beneficiary) {
            $descriptionParts = [
                $beneficiary['name'],
                $beneficiary['location'],
                $beneficiary['address'],
            ];
            $descriptionParts = array_filter($descriptionParts);
            $description = implode("\r\n", $descriptionParts);

            $this->execute(
                'update edmForeignCurrencyPaymentBeneficiary set description = :description where id = :id',
                [':description' => $description, ':id' => $beneficiary['id']]
            );
        }

        $this->dropColumn($this->tableName, 'name');
        $this->dropColumn($this->tableName, 'location');
        $this->dropColumn($this->tableName, 'address');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'description');
        $this->addColumn($this->tableName, 'name', $this->string());
        $this->addColumn($this->tableName, 'location', $this->string());
        $this->addColumn($this->tableName, 'address', $this->string());
    }
}
