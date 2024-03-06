<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_000000_drop extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `actionLog`');
		$this->execute('DROP TABLE IF EXISTS `contractor`');
		$this->execute('DROP TABLE IF EXISTS `certSignDocument`');
		$this->execute('DROP TABLE IF EXISTS `userHasRole`');
		$this->execute('DROP TABLE IF EXISTS `userHasDocumentType`');
		$this->execute('DROP TABLE IF EXISTS `role`');
		$this->execute('DROP TABLE IF EXISTS `variable`');
		$this->execute('DROP TABLE IF EXISTS `file`');
		$this->execute('DROP TABLE IF EXISTS `rbsLog`');
		$this->execute('DROP TABLE IF EXISTS `messageChunk`');
		$this->execute('DROP TABLE IF EXISTS `documentCatalog`');
		$this->execute('DROP TABLE IF EXISTS `import_document`');
		$this->execute('DROP TABLE IF EXISTS `document`');
		$this->execute('DROP TABLE IF EXISTS `documentType`');
		$this->execute('DROP TABLE IF EXISTS `message`');
		$this->execute('DROP TABLE IF EXISTS `messageEventLog`');
		$this->execute('TRUNCATE TABLE `migration`');
    }

    public function down()
    {
		echo "The migration m150709_000000_drop cannot be undone\n";

		return false;
    }
}
