<?php

namespace addons\edm\models\ConfirmingDocumentInformation;

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\User;
use common\models\vtbxml\documents\ConfDocInquiry138I;
use common\models\vtbxml\documents\ConfDocInquiry138IConfDocPS;
use Yii;
use yii\db\ActiveRecord;

/**
 * Модель для счетов плательщиков
 * @property integer $id
 * @property integer $documentId
 * @property integer $organizationId
 * @property integer $number
 * @property string $correctionNumber
 * @property string $contractPassport
 * @property string $date
 * @property string $person
 * @property string $contactNumber
 * @property integer $terminalId
 * @property string $businessStatus
 * @property string $businessStatusComment
 * @property string $businessStatusDescription
 * @property string $bankBik
 * @property string $uuid
 * @property string $extStatus
 * @property-read string $bankName
 * @property-read DictBank $bank
 * @property-read DictOrganization $organization
 * @property-read Document $document
 * @property-read ConfirmingDocumentInformationItem[] $items
 */
class ConfirmingDocumentInformationExt extends ActiveRecord implements DocumentExtInterface
{
    const SCENARIO_UI_CREATE = 'scenarioLoadUICreate';
    const SCENARIO_UI_UPDATE = 'scenarioLoadUIUpdate';

    public $type = 'ConfirmingDocumentInformation';
    /**
     * @var ConfirmingDocumentInformationItem[]
     */
    public $documents = [];

    public static function tableName()
    {
        return 'edm_confirmingDocumentInformationExt';
    }

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('edm', 'Number document'),
            'contractPassport' => Yii::t('edm', 'Loan agreement (contract) unique number'),
            'correctionNumber' => Yii::t('edm', 'Correction number'),
            'date' => Yii::t('edm', 'Date'),
            'person' => Yii::t('edm', 'Person'),
            'contactNumber' => Yii::t('edm', 'Contact number'),
            'organizationId' => Yii::t('edm', 'Organization'),
            'businessStatus' => Yii::t('edm', 'Business status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description'),
            'businessStatusComment' => Yii::t('other', 'Error description'),
            'bankBik' => Yii::t('edm', 'Bank'),
        ];
    }

    public function rules()
    {
        return [
            [['number', 'contractPassport', 'date', 'documents', 'bankBik'], 'required'],
            [['organizationId'], 'required', 'on' => [static::SCENARIO_UI_CREATE, static::SCENARIO_UI_UPDATE]],
            [['correctionNumber', 'person', 'contactNumber', 'documentId', 'organizationId', 'bankBik', 'extStatus'], 'safe'],
            ['person', 'string', 'max' => '140'],
            [['correctionNumber'], 'number'],
            [['businessStatus', 'businessStatusComment', 'businessStatusDescription', 'bankBik', 'uuid', 'number'], 'string']
        ];
    }

    /**
     * Загрузка данных по документам справки
     */
    public function loadDocuments($documents)
    {
        $this->documents = [];

        // Проверка наличия массива с данными
        if (empty($documents)) {
            $this->addError('documents', null);
            return false;
        }

        // Перебор данных операций и загрузка в модель
        foreach($documents as $document) {
            // Валидация модели
            $document->validate();
            $this->documents[Uuid::generate()] = $document;
        }
    }

    /**
     * Создание связанных со справкой документов
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Удаляем существующие документы
        ConfirmingDocumentInformationItem::deleteAll(['documentId' => $this->documentId]);

        foreach($this->documents as $document) {
            $document->id = null;
            $document->isNewRecord = true;
            $document->documentId = $this->documentId;
            $document->save();
        }
    }

    /**
     * Перед удаление самой справки, удаляем  привязанные к ней операции
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach($this->items as $document) {
            $document->delete();
        }

        return true;
    }

    public function getItems()
    {
        return $this
            ->hasMany(ConfirmingDocumentInformationItem::className(), ['documentId' => 'documentId'])
            ->orderBy(['id' => SORT_ASC]);
    }

    public function getOrganization()
    {
        return $this->hasOne(DictOrganization::className(), ['Id' => 'organizationId']);
    }

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['Id' => 'documentId']);
    }

    public function getOrganizationBankName()
    {
        $bank = EdmHelper::getOrganizationBank($this->organization);

        return $bank->name;
    }

    public function getOrganizationName()
    {
        return $this->organization->name;
    }

    public function loadContentModel($model)
    {
        if ($model instanceof VTBConfDocInquiry138IType) {
            /** @var ConfDocInquiry138I $vtbDocument */
            $vtbDocument = $model->document;
            $organization = VTBHelper::getOrganizationByVTBCustomerId($vtbDocument->CUSTID);

            $this->number = $vtbDocument->DOCUMENTNUMBER === '' || $vtbDocument->DOCUMENTNUMBER === null ? null : (int)$vtbDocument->DOCUMENTNUMBER;
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('d.m.Y') : null;
            $this->organizationId = $organization !== null ? $organization->id : null;
            $this->person = $vtbDocument->SENDEROFFICIALS;
            $this->contractPassport = $vtbDocument->PSNUMBER;
            $this->contactNumber = null;
            $this->bankBik = $vtbDocument->CUSTOMERBANKBIC;

            $this->documents = array_reduce(
                $vtbDocument->CONFDOCPSBLOB,
                function ($carry, ConfDocInquiry138IConfDocPS $vtbConfirmingDocument) {
                    $documentCurrency = DictCurrency::findOne(['code' => $vtbConfirmingDocument->CURRCODE1]);
                    $contractCurrency = DictCurrency::findOne(['code' => $vtbConfirmingDocument->CURRCODE2]);

                    $carry[Uuid::generate()] = new ConfirmingDocumentInformationItem([
                        'number' => $vtbConfirmingDocument->DOCUMENTNUMBER,
                        'date' => $vtbConfirmingDocument->DOCDATE !== null ? $vtbConfirmingDocument->DOCDATE->format('d.m.Y') : null,
                        'code' => $vtbConfirmingDocument->DOCCODE,
                        'sumDocument' => $vtbConfirmingDocument->AMOUNTCURRENCY1,
                        'sumContract' => $vtbConfirmingDocument->AMOUNTCURRENCY2,
                        'currencyIdDocument' => $documentCurrency !== null ? $documentCurrency->id : null,
                        'currencyIdContract' => $contractCurrency !== null ? $contractCurrency->id : null,
                        'type' => $vtbConfirmingDocument->FDELIVERY,
                        'expectedDate' => $vtbConfirmingDocument->EXPECTDATE !== null ? $vtbConfirmingDocument->EXPECTDATE->format('d.m.Y') : null,
                        'countryCode' => $vtbConfirmingDocument->COUNTRYCODE,
                        'comment' => $vtbConfirmingDocument->REMARK
                    ]);
                    return $carry;
                },
                []
            );
        }
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocumentsForBankTerminal($user->id, $document->receiver);
    }

    public function getBank(): ?DictBank
    {
        if (!$this->bankBik) {
            return null;
        }
        return DictBank::findOne(['bik' => $this->bankBik]);
    }

    public function getBankName(): ?string
    {
        $bank = $this->bank;
        return $bank ? $bank->name : null;
    }

    public static function getDeletableStatuses(): array
    {
        return array_merge(
            Document::getDeletableStatus(),
            [Document::STATUS_ACCEPTED]
        );
    }

    public function generateUuid(): void
    {
        $this->uuid = str_replace('-', '', Uuid::generate());
    }
}
