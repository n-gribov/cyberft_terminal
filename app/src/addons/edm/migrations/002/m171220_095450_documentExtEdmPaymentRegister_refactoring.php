<?php

use yii\db\Migration;

class m171220_095450_documentExtEdmPaymentRegister_refactoring extends Migration
{
    public function up()
    {
        $this->execute('alter table edm_paymentRegister add column documentId bigint unsigned');
        $this->execute('alter table edm_paymentOrder add column updated tinyint unsigned not null default 0');
        $this->execute('alter table documentExtEdmPaymentRegister add column accountId bigint unsigned');
        $this->execute('alter table documentExtEdmPaymentRegister add column currency varchar(4)');
        $this->execute('alter table documentExtEdmPaymentRegister add column accountNumber varchar(32)');
        $this->execute('alter table documentExtEdmPaymentRegister add column statusComment varchar(255)');
        $this->execute('alter table documentExtEdmPaymentRegister add column businessStatus varchar(4)');
        $this->execute('alter table documentExtEdmPaymentRegister add column businessStatusDescription varchar(255)');
        $this->execute('alter table documentExtEdmPaymentRegister add column orgId int unsigned');
        $this->execute('alter table documentExtEdmPaymentRegister add column businessStatusComment text');
    }

    public function down()
    {
        $this->execute('alter table edm_paymentRegister drop column documentId');
        $this->execute('alter table edm_paymentOrder drop column updated');
        $this->execute('alter table documentExtEdmPaymentRegister drop column accountId');
        $this->execute('alter table documentExtEdmPaymentRegister drop column currency');
        $this->execute('alter table documentExtEdmPaymentRegister drop column accountNumber');
        $this->execute('alter table documentExtEdmPaymentRegister drop column statusComment');
        $this->execute('alter table documentExtEdmPaymentRegister drop column businessStatus');
        $this->execute('alter table documentExtEdmPaymentRegister drop column businessStatusDescription');
        $this->execute('alter table documentExtEdmPaymentRegister drop column orgId');
        $this->execute('alter table documentExtEdmPaymentRegister drop column businessStatusComment');

        return true;
    }

}
