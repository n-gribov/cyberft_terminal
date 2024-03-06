<?php

namespace backend\models\signing;

use addons\edm\jobs\ForeignCurrencyOperationSignJob;
use addons\edm\jobs\PaymentRegisterSignJob;
use addons\edm\jobs\VTBRegisterSignJob;
use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\UserAuthCertBeneficiary;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Address;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\jobs\DocumentContentSignJob;
use common\jobs\DocumentSignJob;
use common\jobs\RosbankSignJob;
use common\models\CyberSignServiceEncoding;
use common\models\CyberSignServiceSingMethod;
use common\models\cyberxml\CyberXmlDocument;
use common\models\User;
use common\models\UserAuthCert;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\base\Model;

class DocumentsSigningForm extends Model
{
    const SCENARIO_SAVE_SIGNATURES = 'scenarioSaveSignatures';

    public $documentsIds = [];
    public $signatures = [];
    public $certBody;

    private $documents = [];

    public function rules()
    {
        return [
            ['documentsIds', 'required', 'message' => Yii::t('doc', 'Documents not selected')],
            ['documentsIds', 'validateDocuments'],
            ['signatures', 'validateSignatures', 'on' => static::SCENARIO_SAVE_SIGNATURES],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SAVE_SIGNATURES] = ['signatures', 'certBody'];

        return $scenarios;
    }

    public function init()
    {
        parent::init();
    }

    public function validateDocuments($attribute, $params = [])
    {
        foreach ($this->documentsIds as $documentsId) {
            $this->validateDocument($documentsId);
        }
    }

    public function validateSignatures($attribute, $params = [])
    {
        foreach (['certBody', 'signatures'] as $requiredAttribute) {
            if (empty($requiredAttribute)) {
                Yii::info("Got empty $requiredAttribute");
            }
        }

        $hasEmptySignature = in_array(null, $this->signatures);
        if ($hasEmptySignature) {
            Yii::info('Got empty empty value in signatures array');
        }

        if (empty($this->signatures) || empty($this->certBody) || $hasEmptySignature) {
            $this->addError($attribute, Yii::t('doc', 'Signing has failed'));
        }
    }

    public function extractDocumentsSignedData()
    {
        if (!$this->validate()) {
            return [false, null];
        }

        $documentsSignedData = array_map(
            function ($documentId) {
                return $this->extractDocumentSignedData($documentId);
            },
            $this->documentsIds
        );

        return [true, $documentsSignedData];
    }

    public function saveSignatures()
    {
        $this->scenario = static::SCENARIO_SAVE_SIGNATURES;
        if (!$this->validate()) {
            return false;
        }

        $success = true;
        $jobsIds = [];

        $signaturesData = [];

        foreach($this->documentsIds as $index => $documentId) {
            $signature = $this->signatures[$index];

            if (isset($signaturesData[$documentId])) {
                $signaturesData[$documentId][] = $signature;
            } else {
                $signaturesData[$documentId] = [$signature];
            }
        }

        foreach ($signaturesData as $documentId => $signatures) {
            if (count($signatures) == 1) {
                $signatures = $signatures[0];
            }

            $jobId = $this->saveSignature($documentId, $signatures);

            if ($jobId === false) {
                $success = false;
                $this->addDocumentError(Yii::t('doc', 'Failed to sign document #{id}', ['id' => $documentId]));
            } else {
                $jobsIds[] = $jobId;
            }
        }

        return [$success, $jobsIds];
    }

    public function getErrorsList()
    {
        return array_reduce(
            array_values($this->getErrors()),
            'array_merge',
            []
        );
    }

    private function validateDocument($id)
    {
        $document = $this->getDocumentById($id);
        if ($document === null) {
            $this->addDocumentError(Yii::t('doc', 'Document #{id} is not found', ['id' => $id]));

            return;
        }

        if ($document->signaturesRequired > 0 && $document->signaturesRequired == $document->signaturesCount) {
            $this->addDocumentError(Yii::t('doc', 'All required signatures already added to document #{id}', ['id' => $id]));

            return;
        }

        if (!$document->isSignable()) {
            $this->addDocumentError(Yii::t('doc', 'Document #{id} does not require personal signing due to current settings', ['id' => $id]));

            return;
        }

        if (!$document->isSignableByUserLevel($document->typeGroup)) {
            $this->addDocumentError(
                Yii::t(
                    'document',
                    'Your signature number ({number}) does not allow you to sign document #{id} now',
                    [
                        'number' => Yii::$app->user->identity->signatureNumber,
                        'id' => $id
                    ]
                )
            );

            return;
        }

        if (RosbankHelper::isTerminalUsingRosbankFormat($document->receiver) && !Yii::$app->user->identity->signatureLevel) {
            $this->addDocumentError(
                Yii::t(
                    'doc',
                    'Your signature level is not set. Please, contact your administrator to resolve tis issue.'
                )
            );

            return;
        }

        /*
         * Если у пользователя в настройках есть сертификаты, но нет сертификата, который
         * подходит для получателя, указанного в документе, то создаем ошибку валидации
         */
        if ($this->findUserCertificateBody($document->receiver, $document->sender) === 'non-valid') {
            $user = Yii::$app->user->identity;
            $senderName = $user->lastName . ' ' . $user->firstName . ' ' . $user->middleName;
            if (strlen($senderName) < 3) {
                $senderName = $document->sender;
            }
            $receiverName = $document->receiver;
            $participant = BICDirParticipant::findOne([
                'participantBIC' => Address::truncateAddress($document->receiver)
            ]);
            if ($participant) {
                $receiverName = $participant->name;
            }

            $this->addDocumentError(
                Yii::t(
                    'doc',
                    'Signing Error! There is no defined key for receiver {receiver} ({receiverTerminal}) in sender {sender}\'s settings. Assign signing certificate for current receiver in \'My Keys\' menu or contact terminal administrator.',
                    ['receiver' => $receiverName, 'sender' => $senderName, 'receiverTerminal' => $document->receiver]
                )
            );
        }
    }

    private function addDocumentError($errorMessage)
    {
        $this->addError('documentsIds', $errorMessage);
    }

    /**
     * @param $id
     * @return Document|null
     */
    private function getDocumentById($id)
    {
        if (!array_key_exists($id, $this->documents)) {
            $this->documents[$id] = $this->findDocumentById($id);
        }
        return $this->documents[$id];
    }

    private function findDocumentById($id)
    {
        /** @var Document $document */
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        if ($document === null) {
            Yii::info("Document $id does not exist or belongs to terminal unavailable to current user");

            return null;
        }

        if (!$this->userCanSignDocument($document)) {
            Yii::info("Document $id belongs to type group unavailable to current user for signing");

            return null;
        }

        if (!$this->userHasDocumentAccountAccess($document)) {
            Yii::info("Document $id has account unavailable to current user");

            return null;
        }

        return $document;
    }

    private function userCanSignDocument(Document $document)
    {
        return Yii::$app->user->can(
            DocumentPermission::SIGN,
            [
                'serviceId' => $document->typeGroup,
                'document' => $document,
            ]
        );
    }

    private function userHasDocumentAccountAccess(Document $document)
    {
        $user = Yii::$app->user->identity;
        if ($user->role === User::ROLE_ADMIN || $user->role === User::ROLE_ADDITIONAL_ADMIN) {
            return true;
        }

        $extModel = $document->extModel;
        $accountNumber = null;
        if ($extModel instanceof PaymentRegisterDocumentExt) {
            $accountNumber = $extModel->accountNumber;
        } else if ($extModel instanceof ForeignCurrencyOperationDocumentExt) {
            $accountNumber = $extModel->debitAccount;
        } else {
            return true;
        }

        $allowedAccountsNumbers = EdmPayerAccountUser::getUserAllowAccountsNumbers($user->id);
        $userHasAccountAccess = in_array($accountNumber, $allowedAccountsNumbers);

        return $userHasAccountAccess;
    }

    private function extractDocumentSignedData($documentId)
    {
        $document = $this->getDocumentById($documentId);

        $userCertificateBody = $this->findUserCertificateBody($document->receiver, $document->sender);

        if ($document->isEncrypted) {
            Yii::$app->terminals->setCurrentTerminalId($document->sender);
            $data = Yii::$app->storage->decryptStoredFile($document->actualStoredFileId);
            $cyxDoc = new CyberXmlDocument();
            $cyxDoc->loadXml($data);
        } else {
            $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        }

        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $signMethod = CyberSignServiceSingMethod::ANY;
        $signedDataEncoding = CyberSignServiceEncoding::RAW;
        $signedData = method_exists($typeModel, 'getSignedInfo')
            ? $typeModel->getSignedInfo($userCertificateBody)
            : $document->getSignData();

        if ($typeModel instanceof BaseVTBDocumentType) {
            $signedData = base64_encode($signedData);
            $signMethod = CyberSignServiceSingMethod::PKCS7;
            $signedDataEncoding = CyberSignServiceEncoding::BASE64;
        }

        if ($typeModel instanceof VTBRegisterRuType ||
            $typeModel instanceof VTBRegisterCurType) {
            $signedData = array_map(function($data) {
                return base64_encode($data);
            }, $signedData);

            $signMethod = CyberSignServiceSingMethod::PKCS7;
            $signedDataEncoding = CyberSignServiceEncoding::BASE64;
        }

        if (RosbankHelper::isTerminalUsingRosbankFormat($document->receiver)) {
            if (property_exists($typeModel, 'rosbankEnvelope')) {
                $signMethod = CyberSignServiceSingMethod::PKCS7;
                $signedDataEncoding = CyberSignServiceEncoding::BASE64;
                $signedData = base64_encode((string) $typeModel);
            }
        }

        return [$signedData, $signMethod, $signedDataEncoding, $userCertificateBody];
    }

    private function saveSignature($documentId, $signature)
    {
        $document = $this->getDocumentById($documentId);
        $signJobClass = $this->getSignJobClass($document);
        DocumentHelper::updateDocumentStatus($document, Document::STATUS_SIGNING);

        $jobId = Yii::$app->resque->enqueue(
            $signJobClass,
            [
                'id'             => $document->id,
                'signature'      => $signature,
                'certBody'       => $this->certBody,
                'signatureLevel' => Yii::$app->user->identity->signatureLevel,
            ]
        );

        if ($jobId === false){
            Yii::info("Failed enqueue $signJobClass for document $documentId");
            DocumentHelper::updateDocumentStatus($document, Document::STATUS_FORSIGNING);

            return false;
        }

        Yii::$app->monitoring->log(
            'user:signDocument',
            'document',
            $documentId,
            [
                'userId' => Yii::$app->user->id,
                'docType' => $document->typeGroup . $document->type,
                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
            ]
        );

        return $jobId;
    }

    private function getSignJobClass(Document $document)
    {
        $typeModelClass = yii::$app->registry->getTypeModelClass($document->type);
        if ($document->type === 'MT103') {
            return ForeignCurrencyOperationSignJob::class;
        } else if ($document->type === PaymentRegisterType::TYPE) {
            return PaymentRegisterSignJob::class;
        } else if (is_subclass_of($typeModelClass, BaseVTBDocumentType::class)
            || is_a($typeModelClass, Pain001Type::class, true)
            || $document->type === Auth024Type::TYPE
            || $document->type === Auth025Type::TYPE
            || $document->type === Auth026Type::TYPE
        ) {
            if (RosbankHelper::isTerminalUsingRosbankFormat($document->receiver)) {
                return RosbankSignJob::class;
            }

            return DocumentContentSignJob::class;
        } else if ($document->type == VTBRegisterRuType::TYPE || $document->type == VTBRegisterCurType::TYPE) {
            return VTBRegisterSignJob::class;
        }

        return DocumentSignJob::class;
    }

    private function findUserCertificateBody($receiver, $sender): ?string
    {
        $certificates = UserAuthCert::find()
            ->where(['userId' => Yii::$app->user->id, 'status' => 'active'])
            ->all();

        if (!count($certificates)) {
            return null;
        }

        foreach ($certificates as $item) {
            if (!$item->status) {
                continue;
            }

            $certBeneficiaries = UserAuthCertBeneficiary::find()
                ->where(['keyId' => $item->id])
                ->all();

            if (empty($certBeneficiaries)) {
                $certificate = $item;
                continue;
            }

            foreach ($certBeneficiaries as $beneficiary) {
                if ($beneficiary->terminalId == $receiver) {
                    $certificate = $item;
                    break 2;
                }
            }
        }

        if (!isset($certificate)) {
            //$errorMessage = Yii::t('doc', 'Signing Error! There is no defined key for receiver ({receiver}) in sender\'s ({sender}) settings! Assign signing certificate for current receiver in \'My Keys\' menu or contact terminal administrator!', $receiver, $sender);
            //Yii::$app->session->setFlash('error', $errorMessage);
            return 'non-valid';
        }

        return $certificate->certificate;
    }
}
