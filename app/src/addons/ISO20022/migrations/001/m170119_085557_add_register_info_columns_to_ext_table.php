<?php

use yii\db\Migration;

class m170119_085557_add_register_info_columns_to_ext_table extends Migration
{
    public function up()
    {
        // @CYB-3460
        // Миграция создает дополнительные поля для extModel ISO20022.
        // Нужны для pain.001, для вывода информации в журнале документов
        $this->addColumn('documentExtISO20022', 'count', $this->string());
        $this->addColumn('documentExtISO20022', 'currency', $this->string(10));
        $this->addColumn('documentExtISO20022', 'sum', $this->string());
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'count');
        $this->dropColumn('documentExtISO20022', 'currency');
        $this->dropColumn('documentExtISO20022', 'sum');

        return true;
    }
}
