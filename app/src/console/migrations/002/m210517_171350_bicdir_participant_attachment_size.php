<?php

use yii\db\Migration;

/**
 * Class m210517_171350_bicdir_participant_attachment_size
 */
class m210517_171350_bicdir_participant_attachment_size extends Migration
{
    public function up()
    {
        $this->execute('alter table participant_BICDir add column maxAttachmentSize int unsigned default null');
    }

    public function down()
    {
        $this->execute('alter table participant_BICDir drop column maxAttachmentSize');

        return true;
    }
}
