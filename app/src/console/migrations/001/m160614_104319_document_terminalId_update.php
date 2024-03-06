<?php

use common\document\Document;
use common\models\Terminal;
use yii\db\Migration;

class m160614_104319_document_terminalId_update extends Migration
{
    public function up()
    {
        $terminals = Terminal::getList('id', 'terminalId');
        
        foreach($terminals as $id => $terminalId) {
            Document::updateAll(
                    ['terminalId' => $id],
                    [
                        'and',
                        [
                            'direction' => Document::DIRECTION_OUT,
                            'sender' => $terminalId
                        ]
                    ]
            );

            Document::updateAll(
                    ['terminalId' => $id],
                    [
                        'and',
                        [
                            'direction' => Document::DIRECTION_IN,
                            'receiver' => $terminalId
                        ]
                    ]
            );
        }

        return true;
    }

    public function down()
    {
        Document::updateAll(['terminalId' => null]);

        return true;
    }

}
