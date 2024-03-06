<?php

use common\models\Processing;
use common\settings\AppSettings;
use yii\db\Migration;

/**
 * Class m220111_220446_processing_update
 */
//Так как мы отказываемся от тестового процессинга на Оракле, то необходимо удалить из списка
// адрес "Test Processing CYBERUM@EST"
//и сделать вместо него по умолчанию "Test Processing PSGTEST@PRC".

class m220111_220446_processing_update extends Migration
{
    public function up()
    {
		Processing::deleteAll(['address' => 'CYBERUM@TEST']);
        Processing::updateAll(['isDefault' => 1], ['address' => 'PSGTEST@APRC']);

		/** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');

        if ($appSettings->processing['address'] == 'CYBERUM@TEST') {
            $appSettings->processing['address'] = 'PSGTEST@APRC';
            $appSettings->processing['dsn'] = 'tcp://localhost:40092';
			$appSettings->save();
        }
    }

    public function down()
    {
        echo "This migration cannot be reverted but can be run again.\n";

		return true;
    }
}
