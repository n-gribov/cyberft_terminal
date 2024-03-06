<?php

use yii\db\Migration;

/**
 * Class m220523_162355_cdi_extstatus
 */
class m220523_162355_cdi_extstatus extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->execute('alter table edm_confirmingDocumentInformationExt add column extStatus varchar(32)');
    }

    public function down()
    {
		$this->execute('alter table edm_confirmingDocumentInformationExt drop column extStatus');

        return true;
    }
}
