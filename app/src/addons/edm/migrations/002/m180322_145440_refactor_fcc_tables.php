<?php

use yii\db\Migration;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;

/**
 * Миграция старых данных в новые представления для СВО
 * @task CYB-4069
 */
class m180322_145440_refactor_fcc_tables extends Migration
{
    public function up()
    {
        // Переименование таблицы
        $this->renameTable('edmForeignCurrencyOperationInformation', 'edm_foreignCurrencyOperationInformationExt');

        // Добавление documentId в подчиненную таблицу
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'documentId', $this->integer());

        // Заполнение documentId в подчиненной таблице
        $rows = (new \yii\db\Query())
            ->select(['id', 'documentId'])
            ->from('edm_foreignCurrencyOperationInformationExt')->all();

        foreach($rows as $row) {
            if ($row['documentId']) {
                ForeignCurrencyOperationInformationItem::updateAll(['documentId' => $row['documentId']], ['informationId' => $row['id']]);
            } else {
                // Если документ был создан, но не отправлен, удаляем его и его подчиненные данные
                ForeignCurrencyOperationInformationExt::deleteAll(['id' => $row['id']]);
                ForeignCurrencyOperationInformationItem::deleteAll(['informationId' => $row['id']]);
            }
        }

        // Удаление лишних колонок из таблиц
        $this->dropColumn('edm_foreignCurrencyOperationInformationExt', 'terminalId');
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'informationId');

        return true;
    }

    public function down()
    {
        $this->renameTable('edm_foreignCurrencyOperationInformationExt', 'edmForeignCurrencyOperationInformation');
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'documentId', $this->integer());
        $this->addColumn('edmForeignCurrencyOperationInformation', 'terminalId', $this->integer());
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'informationId', $this->integer());

        return true;
    }
}
