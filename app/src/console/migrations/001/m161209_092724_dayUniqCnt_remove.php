<?php

use common\helpers\RedisHelper;
use yii\db\Migration;

class m161209_092724_dayUniqCnt_remove extends Migration
{
    public function up()
    {
        $this->execute('drop table if exists dayUniqueCount');

        $counters = Yii::$app->cache->get('document/counters');

        $day = date('z', time());
        if (is_array($counters) && isset($counters['types'])) {
            foreach($counters['types'] as $type => $val) {
                $key = RedisHelper::getKeyName('dayUniqCnt:' . $type . ':' . $day);

                Yii::$app->redis->setex($key, 3600 * 48, $val);

                echo 'Redis key ' . $key . ' set to ' . $val . "\n";
            }
        }
    }

    public function down()
    {
        echo "\n****** WARNING:WARNING:WARNING:WARNING:WARNING:WARNING:WARNING:WARNING ******\n";
        echo "*                                                                           *\n";
        echo "*   Table `dayUniqueCount` is obsolete and does not need to be restored.    *\n";
        echo "*                                                                           *\n";
        echo "*     If you still need this table, answer 'Y', otherwise answer 'N'        *\n";
        echo "*                                                                           *\n";
        echo "************************ Recreate the table? (N/Y) **************************\n\n";

        $line = strtoupper(trim(fgets(STDIN)));

        if ($line == 'Y') {
          $this->execute("CREATE TABLE `dayUniqueCount` (
                `count` bigint(20) unsigned NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `docId` varchar(48) NOT NULL,
                `requestType` varchar(8) NOT NULL,
                `requestData` text NOT NULL,
                PRIMARY KEY (`date`,`count`,`requestType`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        }

        return true;
    }

}
