<?php

use yii\db\Migration;

class m161222_120706_add_fk_edm_organizations_accounts extends Migration
{
    public function up()
    {
        // CYB-3408
        // Принято решение сделать внешний ключ,
        // чтобы при удалении организации удалялись и связанные с ней счета
        $this->addForeignKey(
            'fk_edm_payers_accounts_dict_organization',
            'edmPayersAccounts',
            'organizationId',
            'edmDictOrganization',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_edm_payers_accounts_dict_organization', 'edmPayersAccounts');
    }
}
