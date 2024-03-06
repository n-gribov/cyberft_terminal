<?php

namespace addons\edm\models\ForeignCurrencyControl;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\Countries;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\Terminal;
use common\models\User;
use common\models\vtbxml\documents\CurrDealInquiry181i;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $organizationId
 * @property int $accountId
 * @property string $number
 * @property string $correctionNumber
 * @property string $date
 * @property string $countryCode
 * @property int $documentId
 * @property int $storedFileId
 * @property string $businessStatus
 * @property string $businessStatusDescription
 * @property string $businessStatusComment
 * @property-read string|null $organizationName
 * @property-read Document|null $document
 * @property-read DictOrganization|null $organization
 * @property-read string|null $bankName
 * @property-read Terminal|null $terminal
 * @property-read string|null $accountNumber
 * @property-read ForeignCurrencyOperationInformationItem[] $items
 * @property-read EdmPayerAccount|null $account
 */

class ForeignCurrencyOperationInformationExt extends ActiveRecord implements DocumentExtInterface
{
    const SCENARIO_UI_CREATE = 'scenarioLoadUICreate';
    const SCENARIO_UI_UPDATE = 'scenarioLoadUIUpdate';
    const SCENARIO_IMPORT = 'scenarioImport';

    public $type = 'ForeignCurrencyOperationInformation';
    public $operations = [];

    public static function tableName()
    {
        return 'edm_foreignCurrencyOperationInformationExt';
    }

    public function rules()
    {
        return [
            [['date'], 'required'],
            [['number'], 'required', 'on' => [static::SCENARIO_UI_CREATE, static::SCENARIO_UI_UPDATE]],
            [['organizationId', 'accountId', 'operations'], 'required', 'on' => [static::SCENARIO_UI_CREATE, static::SCENARIO_UI_UPDATE, static::SCENARIO_IMPORT]],
            [['correctionNumber', 'documentId', 'storedFileId', 'countryCode'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'organizationId' => Yii::t('edm', 'Customer Name'),
            'accountId' => Yii::t('edm', 'Account'),
            'number' => Yii::t('edm', 'Document number'),
            'correctionNumber' => Yii::t('edm', 'Correction number'),
            'date' => Yii::t('doc', 'Date'),
            'countryCode' => Yii::t('edm', 'Bank country code'),
            'businessStatus' => Yii::t('edm', 'Business status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description'),
            'businessStatusComment' => Yii::t('other', 'Error description'),
            'authorizedBankId' => 'Уполномоченный банк',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(
            ForeignCurrencyOperationInformationItem::className(),
            ['documentId' => 'documentId']
        );
    }

    /**
     * Загрузка данных по операции валютной справки
     */
    public function loadOperations($operations)
    {
        $this->operations = [];

        // Проверка наличия массива с данными
        if (empty($operations)) {
            $this->addError('operations', null);

            return false;
        }

        // Перебор данных операций и загрузка в модель
        foreach($operations as $operation) {
            // Валидация модели
            $operation->validate();
            $this->operations[Uuid::generate()] = $operation;
        }
    }

    /**
     * Создание связанных с валютной справкой операций
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Удаление связанных операций
        ForeignCurrencyOperationInformationItem::deleteAll(['documentId' => $this->documentId]);

        // Запись операций документа
        /** @var ForeignCurrencyOperationInformationItem $operation */
        foreach($this->operations as $operation) {
            $operation->id = null;
            $operation->isNewRecord = true;
            $operation->documentId = $this->documentId;
            $operation->docRepresentation = (int) $operation->docRepresentation;
            // Сохранить модель в БД
            $result = $operation->save();
        }
    }

    /**
     * Перед удалением самой справки удаляем  привязанные к ней операции
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach($this->items as $operation) {
            // Удалить документ из БД
            $operation->delete();
        }

        return true;
    }

    public function getOrganization()
    {
        return $this->hasOne(DictOrganization::className(), ['id' => 'organizationId']);
    }

    public function getAccount()
    {
        return $this->hasOne(EdmPayerAccount::className(), ['id' => 'accountId']);
    }

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'documentId']);
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    public function getBankName()
    {
        return $this->account->bank->name;
    }

    public function getOrganizationName($asPayerName = true)
    {
        return $asPayerName && $this->account->payerName ? $this->account->payerName : $this->organization->name;
    }

    public function getAccountNumber()
    {
        return $this->account->number;
    }

    public function loadContentModel($model)
    {
        if ($model instanceof VTBCurrDealInquiry181iType) {
            /** @var CurrDealInquiry181i $vtbDocument */
            $vtbDocument = $model->document;

            $organization = VTBHelper::getOrganizationByVTBCustomerId($vtbDocument->CUSTID);
            $account = EdmPayerAccount::findOne(['number' => $vtbDocument->ACCOUNT]);

            $this->organizationId = $organization !== null ? $organization->id : null;
            $this->accountId = $account !== null ? $account->id : null;
            $this->number = $vtbDocument->DOCUMENTNUMBER;
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('d.m.Y') : null;
            $this->correctionNumber = null;
            $this->countryCode = Countries::getAlfaCode($vtbDocument->BANKCOUNTRYCODE);
        }
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocuments($user->id, $this->accountId);
    }
}

