<?php

use yii\db\Migration;

class m161117_140641_add_columns_to_autobot extends Migration
{
    public function up()
    {
        // Добавление новых полей для сертификатов контролеров
        // Фамилия владельца, Имя Отчетчество владельца
        // CYB-3322
        $this->addColumn('autobot', 'ownerSurname', $this->string(64));
        $this->addColumn('autobot', 'ownerName', $this->string(64));

        // Удаление лишнего поля commonName из таблицы autobot
        // Согласно задаче, оно больше не требуется
        // CYB-3322
        $this->dropColumn('autobot', 'commonName');
    }

    public function down()
    {
        $this->dropColumn('autobot', 'ownerSurname');
        $this->dropColumn('autobot', 'ownerName');
        $this->addColumn('autobot', 'commonName', $this->string(64) . ' DEFAULT NULL COMMENT "Common name"');
        return true;
    }
}
