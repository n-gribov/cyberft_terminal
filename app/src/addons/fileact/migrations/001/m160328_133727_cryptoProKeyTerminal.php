<?php

use common\models\CryptoproKeyTerminal;
use yii\db\Migration;

class m160328_133727_cryptoProKeyTerminal extends Migration
{
    public function up()
    {
        $this->execute(
            "create table if not exists `cryptoproKeyTerminal`(
                `keyId` int unsigned not null,
                `terminalId` int unsigned not null,
                unique key(`keyId`, `terminalId`)
            )"
        );

        $command = Yii::$app->db->createCommand('select * from cryptoproKeys');
        $resultSet = $command->query();

        foreach($resultSet as $row) {
            $kt = new CryptoproKeyTerminal (
                [
                    'keyId' => $row['id'],
                    'terminalId' => $row['terminalId'],
                ]
            );

            if (!$kt->save()) {
                echo 'Error saving terminal ' . $row['terminalId'] . ' for key ' . $row['id'] . "\n";
            }
        }
    }

    public function down()
    {
        $this->execute("drop table `cryptoproKeyTerminal`");

        return true;
    }
}
