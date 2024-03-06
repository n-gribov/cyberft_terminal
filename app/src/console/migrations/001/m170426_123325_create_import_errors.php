<?php

use yii\db\Migration;

class m170426_123325_create_import_errors extends Migration
{
    public function up()
    {
        // Таблица для хранения записей
        // ошибочных попыток импорта документов
        // CYB-3612
        $this->createTable('import_errors', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'dateCreate' => $this->dateTime(),
            'identity' => $this->string(),
            'filename' => $this->string(),
            'errorDescription' => $this->text()
        ]);
    }

    public function down()
    {
        $this->dropTable('import_errors');
    }
}
