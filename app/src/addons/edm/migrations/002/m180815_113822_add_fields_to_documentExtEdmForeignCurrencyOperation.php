<?php

use addons\edm\EdmModule;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use yii\db\Migration;
use yii\helpers\ArrayHelper;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;

/**
 * Добавление поля "DocumentType" для дополнительной структуризации документов,
 * у которых одинаковый основной тип
 * Добавление значений сумм по дебету и кредиту
 * @task CYB-4200
 */
class m180815_113822_add_fields_to_documentExtEdmForeignCurrencyOperation extends Migration
{
    private $_tableName = 'documentExtEdmForeignCurrencyOperation';

    public function up()
    {
        $this->addColumn($this->_tableName, 'documentType', $this->string());

        $documentType = ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT;

        // Существующим документам EDM pain.001 ставим тип 'ForeignCurrencySellTransitAccount'
        $documents = Document::find()->select('id')
            ->where(
                [
                    'type' => Pain001Type::TYPE,
                    'typeGroup' => EdmModule::SERVICE_ID
                ]
            )->asArray()->all();

        $ids = ArrayHelper::getColumn($documents, 'id');

        ForeignCurrencyOperationDocumentExt::updateAll(['documentType' => $documentType], ['documentId' => $ids]);

        $this->addColumn($this->_tableName, 'debitAmount', $this->decimal(18, 2));
        $this->addColumn($this->_tableName, 'creditAmount', $this->decimal(18, 2));
        $this->alterColumn($this->_tableName, 'currencySum', $this->decimal(18, 2));
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'documentType');
        $this->dropColumn($this->_tableName, 'debitAmount');
        $this->dropColumn($this->_tableName, 'creditAmount');
        $this->alterColumn($this->_tableName, 'currencySum', $this->decimal(10, 2));
    }
}
