<?php

namespace addons\edm\models\ContractUnregistrationRequest;

use addons\edm\EdmModule;
use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm\AttachedFileSession;
use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm\Contract;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\models\User;
use common\models\UserTerminal;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\documents\ContractUnReg;
use common\models\vtbxml\documents\ContractUnRegPSRow;
use common\models\vtbxml\service\SignInfo;
use common\modules\transport\helpers\DocumentTransportHelper;
use DateTime;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property DictBank[] $availableReceiverBanks
 * @property DictOrganization[] $availableOrganizations
 * @property DictBank $receiverBank
 * @property DictOrganization $organization
 */
class ContractUnregistrationRequestForm extends Model
{
    /** @var User */
    public $user;

    public $documentNumber;
    public $documentDate;
    public $organizationId;
    public $contactPerson;
    public $contactPhone;
    public $receiverBankBik;

    /** @var Contract[] */
    public $contracts = [];

    /** @var AttachedFileSession[] */
    public $attachedFiles = [];

    public $contractsJson = '[]';
    public $attachedFilesJson = '[]';

    public function rules()
    {
        return [
            [['documentNumber', 'contractsJson', 'attachedFilesJson'], 'string'],
            [['documentDate'], 'date', 'format' => 'dd.MM.yyyy'],
            [
                [
                    'documentNumber',
                    'documentDate',
                    'receiverBankBik',
                    'organizationId',
                    'contactPerson',
                    'contactPhone',
                    'contractsJson',
                    'attachedFilesJson',
                ],
                'safe'
            ],
            [
                [
                    'documentNumber',
                    'documentDate',
                    'receiverBankBik',
                    'organizationId',
                ],
                'required'
            ],
            ['organizationId', 'validateOrganization'],
            ['receiverBankBik', 'validateReceiverBankBik'],
        ];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);

        $this->contracts = Contract::createListFromJson($this->contractsJson);
        $this->attachedFiles = AttachedFileSession::createListFromJson($this->attachedFilesJson);

        return $result;
    }

    public function attributeLabels()
    {
        return [
            'documentNumber'  => Yii::t('edm', 'Document number'),
            'documentDate'    => Yii::t('edm', 'Document date'),
            'receiverBankBik' => Yii::t('edm', 'Authorized bank'),
            'organizationId'  => Yii::t('edm', 'Organization'),
            'contactPerson'   => Yii::t('edm', 'Contact person'),
            'contactPhone'    => Yii::t('edm', 'Contact phone number'),
            'contracts'       => Yii::t('edm', 'Contracts (loan agreements)'),
            'attachedFiles'   => Yii::t('edm', 'Attached files'),
        ];
    }

    /**
     * @return DictOrganization[]
     */
    public function getAvailableOrganizations()
    {
        if ($this->user === null) {
            return [];
        }

        $terminalsIds = $this->user->disableTerminalSelect
            ? UserTerminal::getUserTerminalIndexes($this->user->id)
            : [$this->user->terminalId => $this->user->terminalId];

        $vtbCustomersTerminalsIds = TerminalRemoteId::find()
            ->where(['terminalReceiver' => VTBHelper::getGatewayTerminalAddress()])
            ->andWhere(['terminalId' => $terminalsIds])
            ->select('terminalId')
            ->column();

        return DictOrganization::findAll(['terminalId' => $vtbCustomersTerminalsIds]);
    }

    /**
     * @return DictBank[]
     */
    public function getAvailableReceiverBanks()
    {
        if ($this->user === null) {
            return [];
        }

        $accountsIds = EdmPayerAccountUser::getUserAllowAccounts($this->user->id);
        $bikQuery = EdmPayerAccount::find()->where(['in', 'id', $accountsIds])->select('bankBik');
        $vtbBikQuery = DictVTBBankBranch::find()->select('bik');
        return DictBank::find()
            ->where(['in', 'bik', $bikQuery])
            ->andWhere(['in', 'bik', $vtbBikQuery])
            ->andWhere(['not', ['terminalId' => null]])
            ->all();
    }

    public function createDocument()
    {
        $senderOrganization = DictOrganization::findOne($this->organizationId);
        $receiverBank = DictBank::findOne(['bik' => $this->receiverBankBik]);

        // Создать тайп-модель
        $typeModel = $this->createTypeModel($senderOrganization);
        // Атрибуты документа
        $docAttributes = $this->createDocumentAttributes($senderOrganization, $receiverBank);
        // Атрибуты расширяющей модели
        $extModelAttributes = $this->createExtModelAttributes($typeModel);

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extModelAttributes);
        if (!$context) {
            throw new Exception(\Yii::t('app', 'Save document error'));
        }

        // Получить документ из контекста
        $document = $context['document'];
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);

        return $document;
    }

    public function updateDocument(Document $document)
    {
        $senderOrganization = DictOrganization::findOne($this->organizationId);
        $receiverBank = DictBank::findOne(['bik' => $this->receiverBankBik]);

        // Создать тайп-модель
        $typeModel = $this->createTypeModel($senderOrganization);
        // Атрибуты документа
        $docAttributes = $this->createDocumentAttributes($senderOrganization, $receiverBank);
        // Атрибуты расширяющей модели
        $extModelAttributes = $this->createExtModelAttributes($typeModel);

        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
        $cyxDocument->setTypeModel($typeModel);
        $fileInfo = $cyxDocument->getStoredFile()->updateData($cyxDocument->saveXML());
        if ($fileInfo === null) {
            throw new Exception("Failed to update stored file for document {$document->id}");
        }

        $document->setAttributes($docAttributes, false);
        $documentIsUpdated = $document->save(false);
        if (!$documentIsUpdated) {
            throw new Exception("Failed to update document {$document->id}");
        }

        $extModel = $document->extModel;
        $extModel->setAttributes($extModelAttributes, false);
        $extModelIsUpdated = $extModel->save(false);
        if (!$extModelIsUpdated) {
            throw new Exception("Failed to update ext model for document {$document->id}");
        }

        /** @var EdmModule $module */
        $module = Yii::$app->getModule('edm');
        // Обработать документ в модуле аддона
        $module->processDocument($document, $document->sender, $document->receiver);
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);
    }

    private function createDocumentAttributes(DictOrganization $senderOrganization, DictBank $receiverBank)
    {
        /** @var Terminal $senderTerminal */
        $senderTerminal = $senderOrganization->terminal;
        $receiverTerminalAddress = $receiverBank->terminalId;

        return [
            'sender'             => $senderTerminal->terminalId,
            'receiver'           => $receiverTerminalAddress,
            'type'               => VTBContractUnRegType::TYPE,
            'direction'          => Document::DIRECTION_OUT,
            'origin'             => Document::ORIGIN_WEB,
            'terminalId'         => $senderTerminal->id,
            'status'             => Document::STATUS_CREATING,
            'signaturesRequired' => Yii::$app->getModule('edm')->getSignaturesNumber($senderTerminal->terminalId),
            'signaturesCount'    => 0,
        ];
    }

    private function createTypeModel(DictOrganization $organization)
    {
        $vtbDocumentVersion = 3;
        $vtbCustomerId = VTBHelper::getVTBCustomerId($organization->terminal->terminalId);
        $vtbBankBranch = DictVTBBankBranch::findOne(['bik' => $this->receiverBankBik]);

        $vtbDocument = new ContractUnReg([
            'CUSTID'               => $vtbCustomerId,
            'KBOPID'               => $vtbBankBranch->branchId,
            'DOCUMENTNUMBER'       => $this->documentNumber,
            'DOCUMENTDATE'         => DateTime::createFromFormat('!d.m.Y', $this->documentDate) ?: null,
            'SENDEROFFICIALS'      => $this->contactPerson,
            'BANKVKFULLNAME'       => $vtbBankBranch->fullName,
            'CUSTOMERNAME'         => $organization->name,
            'CUSTOMERBANKNAME'     => $vtbBankBranch->name,
            'CUSTOMERINN'          => $organization->inn,
            'PHONEOFFICIALS'       => $this->contactPhone,
            'DOCATTACHMENT'        => $this->createVtbDocumentAttachments(),
        ]);

        $vtbDocument->PSROWS = array_map(
            function (Contract $contract) {
                return new ContractUnRegPSRow([
                    'PSNUMBER' => $contract->uniqueContractNumber,
                    'PSDATE' => DateTime::createFromFormat('!d.m.Y', $contract->uniqueContractNumberDate) ?: null,
                    'CODE' => $contract->unregistrationGroundCode,
                    'GROUND1' => mb_substr($contract->unregistrationGroundName, 0, 255),
                    'GROUND2' => mb_substr($contract->unregistrationGroundName, 255),
                ]);
            },
            $this->contracts
        );

        return new VTBContractUnRegType([
            'document' => $vtbDocument,
            'customerId' => $vtbCustomerId,
            'documentVersion' => $vtbDocumentVersion,
            'signatureInfo'  => new SignInfo([
                'signedFields' => $vtbDocument->getSignedFieldsIds($vtbDocumentVersion)
            ])
        ]);
    }

    public function validateOrganization($attribute, $params = [])
    {
        $availableOrganizationsIds = ArrayHelper::getColumn($this->getAvailableOrganizations(), 'id');

        if (!in_array($this->organizationId, $availableOrganizationsIds)) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document on behalf of selected organization'));
        }
    }

    public function validateReceiverBankBik($attribute, $params = [])
    {
        $availableBanksBiks = ArrayHelper::getColumn($this->getAvailableReceiverBanks(), 'bik');

        if (!in_array($this->receiverBankBik, $availableBanksBiks)) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document addressed to selected bank'));
        }
    }

    private function createExtModelAttributes(VTBContractUnRegType $typeModel)
    {
        $extModel = new VTBContractRequestExt();
        $extModel->loadContentModel($typeModel);
        return array_merge(
            $extModel->dirtyAttributes,
            ['contractsAttributes' => $extModel->contractsAttributes]
        );
    }

    public static function createFromDocument(Document $document, User $user, $extractAttachedFiles = false)
    {
        /** @var VTBContractUnRegType $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

        /** @var ContractUnReg $vtbDocument */
        $vtbDocument = $typeModel->document;

        /** @var DictOrganization $organization */
        $organization = DictOrganization::findOne(['terminalId' => $document->terminalId]);

        $vtbBankBranch = DictVTBBankBranch::findOne(['branchId' => $vtbDocument->KBOPID]);

        $form = new ContractUnregistrationRequestForm([
            'user' => $user,
            'organizationId'  => $organization ? $organization->id : null,
            'documentNumber'  => $vtbDocument->DOCUMENTNUMBER,
            'documentDate'    => $vtbDocument->DOCUMENTDATE ? $vtbDocument->DOCUMENTDATE->format('d.m.Y') : null,
            'contactPerson'   => $vtbDocument->SENDEROFFICIALS,
            'receiverBankBik' => $vtbBankBranch ? $vtbBankBranch->bik : null,
            'contactPhone'    => $vtbDocument->PHONEOFFICIALS,
            'contracts' => array_map(
                function ($index) use ($vtbDocument) {
                    $contract = $vtbDocument->PSROWS[$index];
                    return new Contract([
                        'id' => $index + 1,
                        'uniqueContractNumber' => $contract->PSNUMBER,
                        'uniqueContractNumberDate' => $contract->PSDATE ? $contract->PSDATE->format('d.m.Y') : null,
                        'unregistrationGroundCode' => $contract->CODE,
                    ]);
                },
                array_keys($vtbDocument->PSROWS)
            ),
            'attachedFiles' => array_map(
                function ($index) use ($vtbDocument, $extractAttachedFiles) {
                    $attachment = $vtbDocument->DOCATTACHMENT[$index];
                    $attachedFile = new AttachedFileSession([
                        'id' => Uuid::generate(),
                        'name' => $attachment->fileName,
                    ]);

                    if ($extractAttachedFiles) {
                        $path = tempnam(sys_get_temp_dir(), '');
                        file_put_contents($path, $attachment->fileContent);
                        $attachedFile->save($path);
                        unlink($path);
                    }

                    return $attachedFile;
                },
                array_keys($vtbDocument->DOCATTACHMENT)
            ),
        ]);

        $form->contractsJson = Contract::listToJson($form->contracts);
        $form->attachedFilesJson = AttachedFileSession::listToJson($form->attachedFiles);

        return $form;
    }

    /**
     * @return DictOrganization|null
     */
    public function getOrganization()
    {
        return DictOrganization::findOne(['id' => $this->organizationId]);
    }

    public function getReceiverBank()
    {
        return DictBank::findOne(['bik' => $this->receiverBankBik]);
    }

    private function createVtbDocumentAttachments()
    {
        if (count($this->attachedFiles) === 0) {
            return [];
        }

        $iconsPath = Yii::getAlias('@common/models/vtbxml/documents/resources/attachment');
        $icon16Content = file_get_contents("$iconsPath/icon16.ico");
        $icon32Content = file_get_contents("$iconsPath/icon32.ico");

        return array_map(
            function (AttachedFileSession $attachedFile) use ($icon32Content, $icon16Content) {
                $fileContent = file_get_contents($attachedFile->getPath());
                return new BSDocumentAttachment([
                    'fileName'      => $attachedFile->name,
                    'fileContent'   => $fileContent,
                    'icon16Content' => $icon16Content,
                    'icon32Content' => $icon32Content,
                ]);
            },
            $this->attachedFiles
        );
    }
}
