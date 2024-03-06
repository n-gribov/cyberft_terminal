<?php

use yii\db\Migration;

/**
 * Хранение настройки отключения валидации реквизитов получателя для организации
 * @task CYB-4275
 */
class m180906_092839_add_disable_validation_option_to_organization extends Migration
{
    public function up()
    {
        $this->addColumn('edmDictOrganization', 'disablePayeeDetailsValidation', $this->boolean() . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('edmDictOrganization', 'disablePayeeDetailsValidation');
        return true;
    }
}
