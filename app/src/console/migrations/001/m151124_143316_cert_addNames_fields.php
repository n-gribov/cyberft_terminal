<?php

use yii\db\Migration;

class m151124_143316_cert_addNames_fields extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%cert}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'lastName', 'varchar(255) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'firstName', 'varchar(255) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'middleName', 'varchar(255) DEFAULT NULL');

        if(!$this->fromFullNameToFIO()){
            $this->dropColumn($this->_tableName, 'lastName');
            $this->dropColumn($this->_tableName, 'firstName');
            $this->dropColumn($this->_tableName, 'middleName');

            return FALSE;
        }

        $this->dropColumn($this->_tableName, 'fullName');

        return TRUE;
    }

    public function down()
    {
        $this->addColumn($this->_tableName, 'fullName', 'varchar(255) DEFAULT NULL');

        if(!$this->fromFIOToFullName()){
            $this->dropColumn($this->_tableName, 'fullName');
            return FALSE;
        }

        $this->dropColumn($this->_tableName, 'middleName');
        $this->dropColumn($this->_tableName, 'firstName');
        $this->dropColumn($this->_tableName, 'lastName');

        return TRUE;
    }

    /**
     * Move data from fullName field to FIO fields
     *
     * @return boolean
     */
    private function fromFullNameToFIO()
    {
        try{
            $certs = (new yii\db\Query())
                ->from($this->_tableName)
                ->all();

            foreach ($certs as $value) {
                $fullName = explode(' ', $value['fullName']);
                $data = [
                    'lastName' => (!empty($fullName[0])) ? $fullName[0] : '',
                    'firstName' => (!empty($fullName[1])) ? $fullName[1] : '',
                    'middleName' => (!empty($fullName[2])) ? $fullName[2] : '',
                ];

                Yii::$app->db->createCommand()->update($this->_tableName, $data, "id = {$value['id']}")->execute();
            }

            return TRUE;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage());
            return FALSE;
        }
    }

    /**
     * Move data form FIO fileds to fullName field
     *
     * @return boolean
     */
    private function fromFIOToFullName()
    {
        try{
            $certs = (new yii\db\Query())
                ->from($this->_tableName)
                ->all();

            foreach ($certs as $value) {
                $fullName = $value['lastName'] .' '.$value['firstName']. ' '.$value['middleName'];
                $data = [
                    'fullName' => trim(str_replace('  ', ' ', $fullName)),
                ];

                Yii::$app->db->createCommand()->update($this->_tableName, $data, "id = {$value['id']}")->execute();
            }

            return TRUE;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage());
            return FALSE;
        }
    }
}
