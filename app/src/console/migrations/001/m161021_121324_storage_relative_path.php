<?php

use yii\db\Migration;

class m161021_121324_storage_relative_path extends Migration
{
    public function up()
    {
        $this->execute('alter table storage drop column groupKey');
        $this->execute("alter table storage add column fileSystem varchar(64) not null default 'local'");
        /**
         * Отрезать путь до актуального файла, который заканчивается каталогом storage
         */
        $this->execute("update storage set path = substring(path, locate('/storage/', path) + 9) where path like '%/storage/%'");
        /**
         * Далее следует папка сервиса, отрезаем ее тоже
         */
        $this->execute('update storage set path = substring(path, length(serviceId) + 2) where serviceId = substring(path, 1, length(serviceId))');
        /**
         * Далее следует папка ресурса, отрезаем ее тоже
         */
        $this->execute('update storage set path = substring(path, length(resourceId) + 2) where resourceId = substring(path, 1, length(resourceId))');
    }

    public function down()
    {
        $this->execute('alter table storage drop column fileSystem');
        $this->execute('alter table storage add column groupKey varchar(64)');
        $this->execute("update storage set path = concat('/var/www/cyberft/app/storage/', serviceId, '/', resourceId, '/', path)");

        return true;
    }

}
