<?php

use yii\db\Migration;

class m190315_132917_modify_table_indexes extends Migration
{
    public function up()
    {
        $this->execute('alter table `document` drop key `direction`');
        $this->execute('alter table `document` drop key `signaturesRequired`');
        $this->execute('alter table `document` drop key `signaturesCount`');

        $this->execute('alter table `document` add key `srp` (`senderParticipantId`,`receiverParticipantId`)');
        $this->execute('alter table `document` add key `type_status_dir` (`type`,`status`,`direction`)');
        $this->execute('alter table `document` add key `sr_sc` (`signaturesRequired`,`signaturesCount`)');

        $this->execute('alter table `edm_paymentOrder` drop key `status`');
        $this->execute('alter table `edm_paymentOrder` add key `pa_ti_st` (`payerAccount`,`terminalId`,`status`)');

        $this->execute('alter table `edmPayersAccounts` add key `number` (`number`)');

        $this->execute('alter table documentExtEdmStatement modify column accountBik char(9) default null');
    }

    public function down()
    {
        $this->execute('alter table `document` drop key `srp`');
        $this->execute('alter table `document` drop key `type_status_dir`');
        $this->execute('alter table `document` drop key `sr_sc`');

        $this->execute('alter table `document` add key(`direction`)');
        $this->execute('alter table `document` add key(`signaturesRequired`)');
        $this->execute('alter table `document` add key(`signaturesCount`)');

        $this->execute('alter table `edmPayersAccounts` drop key `number`');

        $this->execute('alter table `edm_paymentOrder` drop key `pa_ti_st`');
        $this->execute('alter table `edm_paymentOrder` add key `status`');

        $this->execute('alter table documentExtEdmStatement modify column accountBik int(15) default null');

        return true;
    }
}
