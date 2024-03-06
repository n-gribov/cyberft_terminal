<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_150444_edmDictBank_city_field extends Migration
{
    public function up()
    {
		$this->execute('ALTER TABLE `edmDictBank` MODIFY COLUMN `city` VARCHAR(255) NULL');
    }

    public function down()
    {
        $this->execute('ALTER TABLE `edmDictBank` MODIFY COLUMN `city` VARCHAR(20) NULL');

        return true;
    }

}
