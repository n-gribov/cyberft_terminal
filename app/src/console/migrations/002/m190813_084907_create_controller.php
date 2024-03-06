<?php

use yii\db\Migration;

/**
 * Class m190813_084907_create_controller
 */
class m190813_084907_create_controller extends Migration
{
    private $controllerTableName = '{{%controller}}';
    private $autobotTableName = '{{%autobot}}';
    private $terminalTableName = '{{%terminal}}';

    public function safeUp()
    {
        $this->createTable(
            $this->controllerTableName,
            [
                'id'              => $this->primaryKey(),
                'firstName'       => $this->string(),
                'middleName'      => $this->string(),
                'lastName'        => $this->string(),
                'country'         => $this->string(),
                'stateOrProvince' => $this->string(),
                'locality'        => $this->string(),
                'terminalId'      => $this->integer()->notNull(),
            ]
        );

        $this->addColumn($this->autobotTableName, 'controllerId', $this->integer()->notNull());
        $this->moveTerminalIdToController();
        $this->dropColumn($this->autobotTableName, 'terminalId');
    }

    public function safeDown()
    {
        $this->addColumn($this->autobotTableName, 'terminalId', $this->string(12)->notNull());
        $this->moveTerminalIdBackToAutobot();
        $this->dropColumn($this->autobotTableName, 'controllerId');

        $this->dropTable($this->controllerTableName);
    }

    private function moveTerminalIdToController()
    {
        $terminalAddresses = (new \yii\db\Query())
            ->select(['terminalId'])
            ->distinct()
            ->from($this->autobotTableName)
            ->column();

        foreach ($terminalAddresses as $terminalAddress) {
            try {
                $controllerId = $this->createController($terminalAddress);
                if ($controllerId) {
                    $this->update(
                        $this->autobotTableName,
                        ['controllerId' => $controllerId],
                        ['terminalId' => $terminalAddress]
                    );
                }
            } catch (\Exception $exception) {
                echo "Failed to create controller for terminal $terminalAddress, caused by $exception\n";
            }
        }
    }

    private function moveTerminalIdBackToAutobot()
    {
        $controllers = (new \yii\db\Query())
            ->select(['id', 'terminalId'])
            ->from($this->controllerTableName)
            ->all();

        foreach ($controllers as $controller) {
            try {
                $terminalAddress = $this->getTerminalAddressById($controller['terminalId']);
                $this->update(
                    $this->autobotTableName,
                    ['terminalId' => $terminalAddress],
                    ['controllerId' => $controller['id']]
                );
            } catch (\Exception $exception) {
                echo "Failed to update autobot, caused by $exception\n";
            }
        }
    }

    private function createController($terminalAddress)
    {
        $terminalId = $this->getTerminalIdByAddress($terminalAddress);
        if (!$terminalId) {
            echo "Cannot find terminal $terminalAddress in database\n";
            return null;
        }
        $this->insert(
            $this->controllerTableName,
            ['terminalId' => $terminalId]
        );
        return $this->db->pdo->lastInsertId('controller');
    }

    private function getTerminalIdByAddress($address)
    {
        $terminal = (new \yii\db\Query())
            ->select('id')
            ->from($this->terminalTableName)
            ->where(['terminalId' => $address])
            ->one();
        return $terminal === null ? null : $terminal['id'];
    }

    private function getTerminalAddressById($id)
    {
        $terminal = (new \yii\db\Query())
            ->select('terminalId')
            ->from($this->terminalTableName)
            ->where(['id' => $id])
            ->one();
        return $terminal === null ? null : $terminal['terminalId'];
    }
}
