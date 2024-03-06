<?php

use yii\db\Migration;

/**
 * Добавление дополнительных полей для справочника организаций
 * @task CYB-3857
 */
class m171009_074518_modify_edmDictOrganization extends Migration
{
    public function up()
    {
        $this->addColumn('edmDictOrganization', 'ogrn', $this->string(13));
        $this->addColumn('edmDictOrganization', 'dateEgrul', $this->string());
        $this->addColumn('edmDictOrganization', 'state', $this->string());
        $this->addColumn('edmDictOrganization', 'city', $this->string());
        $this->addColumn('edmDictOrganization', 'street', $this->string());
        $this->addColumn('edmDictOrganization', 'building', $this->string());
        $this->addColumn('edmDictOrganization', 'district', $this->string());
        $this->addColumn('edmDictOrganization', 'locality', $this->string());
        $this->addColumn('edmDictOrganization', 'buildingNumber', $this->string());
        $this->addColumn('edmDictOrganization', 'apartment', $this->string());
    }

    public function down()
    {
        $this->dropColumn('edmDictOrganization', 'ogrn');
        $this->dropColumn('edmDictOrganization', 'dateEgrul');
        $this->dropColumn('edmDictOrganization', 'state');
        $this->dropColumn('edmDictOrganization', 'city');
        $this->dropColumn('edmDictOrganization', 'street');
        $this->dropColumn('edmDictOrganization', 'building');
        $this->dropColumn('edmDictOrganization', 'district');
        $this->dropColumn('edmDictOrganization', 'locality');
        $this->dropColumn('edmDictOrganization', 'buildingNumber');
        $this->dropColumn('edmDictOrganization', 'apartment');

        return true;
    }

}
