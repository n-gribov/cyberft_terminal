<?php

use yii\db\Migration;

class m190125_143710_fcoi_add_new_fields extends Migration
{
    public function up()
    {
        $this->execute('alter table `edm_foreignCurrencyOperationInformationExt` add column `extStatus` varchar(64)');
        $this->execute('alter table `edm_foreignCurrencyOperationInformationExt` add column `authorizedBankId` int unsigned');
        $this->execute('alter table `edmForeignCurrencyOperationInformationItem` add column `docRepresentation` tinyint not null');
    }

    public function down()
    {
        $this->execute('alter table `edm_foreignCurrencyOperationInformationExt` drop column `extStatus`');
        $this->execute('alter table `edm_foreignCurrencyOperationInformationExt` drop column `authorizedBankId`');
        $this->execute('alter table `edmForeignCurrencyOperationInformationItem` drop column `docRepresentation`');

        return true;
    }
}
