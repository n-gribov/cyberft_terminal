<?php

use yii\db\Migration;
use common\modules\certManager\models\Cert;

class m161021_120758_add_statusDescription_column_to_cert extends Migration
{
    public function up()
    {
        // Добавление нового поля для таблицы сертификатов
        // Необходимо для хранения описания к событию смены статуса сертификата
        // CYB-2978

        $this->addColumn('cert', 'statusDescription', $this->string());

        // К той же задаче
        // Все текущие сертификаты терминала считаем активными
        // Получаем список сертификатов и устанавливаем им статус активных

        Cert::updateAll(['status' => Cert::STATUS_C10]);
    }

    public function down()
    {
        $this->dropColumn('cert', 'statusDescription');
        return true;
    }
}
