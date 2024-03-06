<?php

use yii\db\Migration;
use yii\db\Query;

class m210726_122227_make_sbbol_certificate_serial_non_unique extends Migration
{
    private const TABLE_NAME = '{{%sbbol_certificate}}';

    public function safeUp()
    {
        $this->dropSerialIndexes();
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME, 'serial', $this->string()->unique()->notNull());
    }

    private function dropSerialIndexes()
    {
        $indexNames = (new Query())
            ->select(['index_name'])
            ->from('information_schema.statistics')
            ->where(['table_name' => 'sbbol_certificate'])
            ->andWhere(['column_name' => 'serial'])
            ->column();
        foreach ($indexNames as $indexName) {
            $this->dropIndex($indexName, self::TABLE_NAME);
        }
    }
}
