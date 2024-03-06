<?php

use yii\db\Migration;

class m160217_132403_extForeignCurrencyUpdate extends Migration
{
    public function up()
    {
        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                change column `paymentAccount` `debitAccount` varchar(32) NOT NULL"
        );

        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                change column `sum` `currencySum` decimal(10,0) DEFAULT '0'"
        );

        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                add column `creditAccount` varchar(32) NOT NULL after `debitAccount`"
        );
        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                add column `sum` decimal(10,0) DEFAULT '0'"
        );
    }

    public function down()
    {
        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                change column `debitAccount` `paymentAccount` varchar(32) NOT NULL"
        );

        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                drop column `creditAccount`"
        );

        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                drop column `sum`"
        );

        $this->execute(
                "alter table `documentExtEdmForeignCurrencyOperation`
                change column `currencySum` `sum` decimal(10,0) DEFAULT '0'"
        );

        return true;
    }

}
