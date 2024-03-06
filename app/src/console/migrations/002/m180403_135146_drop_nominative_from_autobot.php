<?php

use yii\db\Migration;

/**
 * Удаление ненужной колонки из таблицы ключей контролера
 * @task CYB-3942
 */
class m180403_135146_drop_nominative_from_autobot extends Migration
{
    public function up()
    {
        $this->dropColumn('autobot', 'nominative');
        return true;
    }

    public function down()
    {
        $this->addColumn('autobot', 'nominative', $this->smallInteger() . ' DEFAULT 0');
        return true;
    }

}
