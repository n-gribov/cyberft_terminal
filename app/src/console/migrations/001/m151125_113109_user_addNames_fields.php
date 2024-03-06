<?php

use yii\db\Migration;

class m151125_113109_user_addNames_fields extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%user}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'lastName', 'varchar(45) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'firstName', 'varchar(45) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'middleName', 'varchar(45) DEFAULT NULL');

        if (!$this->fromNameToFIO()){
            $this->dropColumn($this->_tableName, 'middleName');
            $this->dropColumn($this->_tableName, 'firstName');
            $this->dropColumn($this->_tableName, 'lastName');

            return false;
        }

        $this->dropColumn($this->_tableName, 'name');

        return true;
    }

    public function down()
    {
        $this->addColumn($this->_tableName, 'name', 'varchar(45) DEFAULT NULL');

        if (!$this->fromFIOToName()){
            $this->dropColumn($this->_tableName, 'name');

            return false;
        }

        $this->dropColumn($this->_tableName, 'middleName');
        $this->dropColumn($this->_tableName, 'firstName');
        $this->dropColumn($this->_tableName, 'lastName');

        return true;
    }

    /**
     * Move data from name field to FIO fields
     *
     * @return boolean
     */
    private function fromNameToFIO()
    {
        try{
            $certs = (new yii\db\Query())
                ->from($this->_tableName)
                ->all();

            foreach ($certs as $value) {
                $name = explode(' ', $value['name']);
                $data = [
                    'lastName' => (!empty($name[0])) ? $name[0] : '',
                    'firstName' => (!empty($name[1])) ? $name[1] : '',
                    'middleName' => (!empty($name[2])) ? $name[2] : '',
                ];

                Yii::$app->db->createCommand()->update($this->_tableName, $data, "id = {$value['id']}")->execute();
            }

            return true;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage());
            return false;
        }
    }

    /**
     * Move data form FIO fileds to name field
     *
     * @return boolean
     */
    private function fromFIOToName()
    {
        try{
            $certs = (new yii\db\Query())
                ->from($this->_tableName)
                ->all();

            foreach ($certs as $value) {
                $name = $value['lastName'] .' '.$value['firstName']. ' '.$value['middleName'];
                $data = [
                    'name' => trim(str_replace('  ', ' ', $name)),
                ];

                Yii::$app->db->createCommand()->update($this->_tableName, $data, "id = {$value['id']}")->execute();
            }

            return true;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage());
            return false;
        }
    }

}