<?php

use yii\db\Migration;

class m170704_075050_add_latin_fields_to_edm_dict_organization extends Migration
{
    public function up()
    {
        // Добавление полей для латинского
        // написания наименования, адреса и местоположения организации
        $this->addColumn('edmDictOrganization', 'nameLatin', $this->string());
        $this->addColumn('edmDictOrganization', 'addressLatin', $this->string());
        $this->renameColumn('edmDictOrganization', 'location', 'locationLatin');
    }

    public function down()
    {
        $this->dropColumn('edmDictOrganization', 'nameLatin');
        $this->dropColumn('edmDictOrganization', 'addressLatin');
        $this->renameColumn('edmDictOrganization', 'locationLatin', 'location');
    }
}
