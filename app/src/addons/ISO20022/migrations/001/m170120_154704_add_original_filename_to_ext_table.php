<?php

use yii\db\Migration;

class m170120_154704_add_original_filename_to_ext_table extends Migration
{
    public function up()
    {
        //@ CYB-3330
        // Добавление поля для хранения имени импортируемого файла (имя исходного файла)
        $this->addColumn('documentExtISO20022', 'originalFilename', $this->string());
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'originalFilename');
    }
}
