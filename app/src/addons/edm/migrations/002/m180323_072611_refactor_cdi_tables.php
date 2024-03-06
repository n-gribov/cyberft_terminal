<?php

use yii\db\Migration;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;

/**
 * Миграция старых данных в новые представления для CПД
 * @task CYB-4069
 */
class m180323_072611_refactor_cdi_tables extends Migration
{
    public function up()
    {
        // Переименование таблицы
        $this->renameTable('edmConfirmingDocumentInformation', 'edm_confirmingDocumentInformationExt');

        // Добавление documentId в подчиненную таблицу
        $this->addColumn('edmConfirmingDocumentInformationItem', 'documentId', $this->integer());

        // Заполнение documentId в подчиненной таблице
        $rows = (new \yii\db\Query())
            ->select(['id', 'documentId'])
            ->from('edm_confirmingDocumentInformationExt')->all();

        foreach($rows as $row) {
            if ($row['documentId']) {
                ConfirmingDocumentInformationItem::updateAll(['documentId' => $row['documentId']], ['informationId' => $row['id']]);
            } else {
                // Если документ был создан, но не отправлен, удаляем его и его подчиненные данные
                ConfirmingDocumentInformationExt::deleteAll(['id' => $row['id']]);
                ConfirmingDocumentInformationItem::deleteAll(['informationId' => $row['id']]);
            }
        }

        // Удаление лишних колонок из таблиц
        $this->dropColumn('edm_confirmingDocumentInformationExt', 'terminalId');
        $this->dropColumn('edmConfirmingDocumentInformationItem', 'informationId');

        return true;
    }

    public function down()
    {
        $this->renameTable('edm_confirmingDocumentInformationExt', 'edmConfirmingDocumentInformation');
        $this->dropColumn('edmConfirmingDocumentInformationItem', 'documentId');
        $this->addColumn('edmConfirmingDocumentInformation', 'terminalId', $this->integer());
        $this->addColumn('edmConfirmingDocumentInformationItem', 'informationId', $this->integer());

        return true;
    }
}
