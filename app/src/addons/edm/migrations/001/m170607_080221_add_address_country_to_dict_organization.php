<?php

use yii\db\Migration;

class m170607_080221_add_address_country_to_dict_organization extends Migration
{
    public function up()
    {
        // Дополнительное поле для оформления валютного платежа
        // @CYB-3747
        $this->addColumn('edmDictOrganization', 'location', $this->string());
    }

    public function down()
    {
        $this->dropColumn('edmDictOrganization', 'location');
    }
}
