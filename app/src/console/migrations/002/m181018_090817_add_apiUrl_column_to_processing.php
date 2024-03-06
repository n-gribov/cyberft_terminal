<?php

use yii\db\Migration;

class m181018_090817_add_apiUrl_column_to_processing extends Migration
{
    private $tableName = '{{%processing}}';
    
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'apiUrl', $this->text());
        $this->fillApiUrls();
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'apiUrl');
    }

    private function fillApiUrls()
    {
        $apiUrls = [
            'CYBERUM@TEST' => 'https://cyberft-api.cyberplat.ru/test',
            'CYBERUM@AFTX' => 'https://cyberft-api.cyberplat.ru/cyberplat',
            'PSGTEST@APRC' => 'https://cyberft-api.cyberplat.ru/test-new',
            'PLATRUM@AFTX' => 'https://cyberft-api.cyberplat.ru/platina',
        ];

        foreach ($apiUrls as $address => $apiUrl) {
            $this->update('processing', ['apiUrl' => $apiUrl], ['address' => $address]);
        }
    }
}
