<?php

namespace addons\SBBOL\models\forms;

use addons\SBBOL\helpers\CryptoProHelper;
use addons\SBBOL\models\SBBOLCustomerKeyOwner;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\SBBOLModule;
use common\helpers\Uuid;
use common\models\Country;
use common\models\sbbolxml\request\CertifRequestType;
use common\models\sbbolxml\request\ParamType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\SBBOLTransportConfig;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class RegisterKeyForm extends Model
{
    const SCENARIO_GENERATE_CERTIFICATE_REQUEST_PARAMS = 'SCENARIO_GENERATE_CERTIFICATE_REQUEST_PARAMS';
    const SCENARIO_CREATE_KEY = 'SCENARIO_CREATE_KEY';

    public $keyOwnerId;
    public $email;
    public $keyPassword;

    /** @var UploadedFile */
    public $certificateRequestFile;

    /** @var UploadedFile */
    public $keyContainerZipFile;

    /** @var UploadedFile */
    public $publicKeyFile;

    public function scenarios()
    {
        return [
            static::SCENARIO_GENERATE_CERTIFICATE_REQUEST_PARAMS => ['keyOwnerId', 'email'],
            static::SCENARIO_CREATE_KEY => ['keyOwnerId', 'email', 'keyPassword', 'certificateRequestFile', 'keyContainerZipFile', 'publicKeyFile'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'keyOwnerId'             => Yii::t('app/sbbol', 'Key owner'),
            'email'                  => Yii::t('app/sbbol', 'Email'),
            'certificateRequestFile' => Yii::t('app/sbbol', 'Certificate request'),
            'keyContainerZipFile'    => Yii::t('app/sbbol', 'Key container'),
            'publicKeyFile'          => Yii::t('app/sbbol', 'Public key'),
            'keyPassword'            => Yii::t('app/sbbol', 'Key container password'),
        ];
    }

    public function rules()
    {
        return [
            [['keyOwnerId', 'keyPassword'], 'string'],
            ['email', 'email'],
            [['keyOwnerId', 'email'], 'required'],
            [['certificateRequestFile', 'keyContainerZipFile', 'publicKeyFile'], 'file'],
            [['certificateRequestFile', 'keyContainerZipFile', 'publicKeyFile'], 'required', 'on' => static::SCENARIO_CREATE_KEY],
            ['keyPassword', 'default', 'value' => null],
            [
                'keyOwnerId',
                'exist',
                'skipOnError' => true,
                'targetClass' => SBBOLCustomerKeyOwner::className(),
                'targetAttribute' => ['keyOwnerId' => 'id']
            ],
        ];
    }

    public static function getKeyOwnerSelectOptions()
    {
        $keyOwners = SBBOLCustomerKeyOwner::find()
            ->joinWith('customer as customer')
            ->where(['customer.isHoldingHead' => 1])
            ->orderBy('fullName')->all();

        return array_reduce(
            $keyOwners,
            function ($carry, SBBOLCustomerKeyOwner $keyOwner) {
                $carry[$keyOwner->id] = "{$keyOwner->fullName}, {$keyOwner->customer->shortName}";
                return $carry;
            },
            []
        );
    }

    public function generateCertificateRequestParams()
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($this->keyOwnerId);
        $customer = $keyOwner->customer;
        $countryCode = Country::convertCodeToAlfa2($customer->countryCode);
        $subject = sprintf(
            'C=%s,L=%s,O=%s,OU=%s,E=%s,CN=%s,T=%s',
            $countryCode,
            $customer->addressSettlement,
            $customer->shortName,
            $customer->bankBranchSystemName,
            $this->email,
            $keyOwner->fullName,
            $keyOwner->position
        );

        return [
            'subject'    => $subject,
            'bicryptId'  => $this->createBicryptId($keyOwner),
            'exportable' => 1,
        ];
    }

    public function createKey()
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($this->keyOwnerId);
        $customer = $keyOwner->customer;
        if (!$customer->isHoldingHead) {
            throw new \Exception('Key owner does not belong to holding head organization');
        }

        $containerName = null;
        try {
            $containerName = CryptoProHelper::importContainerFromZipFile($this->keyContainerZipFile->tempName);
        } catch (\Exception $exception) {
            Yii::info("Container is not imported: $exception");
            return [false, Yii::t('app/sbbol', 'Failed to import CryptoPro container'), null, null];
        }

        $key = new SBBOLKey([
            'keyOwnerId'         => $keyOwner->id,
            'keyPassword'        => $this->keyPassword,
            'keyContainerName'   => $containerName,
            'status'             => SBBOLKey::STATUS_CREATED,
            'certificateRequest' => file_get_contents($this->certificateRequestFile->tempName),
            'publicKey'          => file_get_contents($this->publicKeyFile->tempName),
            'bicryptId'          => static::createBicryptId($keyOwner),

        ]);
        $isSaved = $key->save();
        if (!$isSaved) {
            Yii::info('Failed to save key to database, errors: ' . var_export($key->getErrors(), true));
            return [false, Yii::t('app/sbbol', 'Failed to save key to database'), null, null];
        }

        list($isSent, $requestId) = $this->sendCertificateRequest(
            $keyOwner,
            $key->certificateRequest,
            $this->email,
            $key->id
        );

        if (!$isSent) {
            Yii::info('Failed to send certificate request to SBBOL, key will be deleted');
            $key->delete();
            return [false, Yii::t('app/sbbol', 'Failed to get send certificate request'), null, null];
        }

        $key->status = SBBOLKey::STATUS_CERTIFICATE_REQUEST_IS_SENT;
        $key->save();

        return [true, null, $requestId, $key->id];
    }

    private function sendCertificateRequest(SBBOLCustomerKeyOwner $keyOwner, $certificateRequest, $certificateEmail, $keyId)
    {
        $certRequestId = Uuid::generate(false)->toString();
        $documentExtId = Uuid::generate(false)->toString();
        $docNum = substr(time(), strlen(time()) - 6);

        $customer = $keyOwner->customer;
        $countryCode = Country::convertCodeToAlfa2($customer->countryCode);

        $certRequestDocument = (new CertifRequestType())
            ->setRequestId($certRequestId)
            ->setDocDate(new \DateTime())
            ->setDocNum($docNum)
            ->setIdCrypto($keyOwner->signDeviceId)
            ->setDocExtId($documentExtId)
            ->setCommonName($keyOwner->fullName)
            ->setOrganization($customer->shortName)
            ->setOrganizationUnit($customer->bankBranchSystemName)
            ->setLocality($customer->addressSettlement)
            ->setCountry($countryCode)
            ->setEmail($certificateEmail)
            ->setPosition($keyOwner->position);

        $bicryptId = static::createBicryptId($keyOwner);

        $certRequestDocument->setDocs([
            (new CertifRequestType\DocsAType\DocAType())
                ->setType('sign')
                ->setAttachment(
                    (new CertifRequestType\DocsAType\DocAType\AttachmentAType())
                        ->setAttachmentName(Uuid::generate(false)->toString() . '.p10')
                        ->setBody(base64_encode($certificateRequest))
                )
                ->addToParams(
                    (new ParamType())
                        ->setName('bicryptId')
                        ->setValue($bicryptId)
                )
        ]);

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setCertifRequest($certRequestDocument);

        /** @var SBBOLModule $module */
        $module = \Yii::$app->getModule(SBBOLModule::SERVICE_ID);
        $sessionId = $module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $sendResult = $module->transport->sendAsync(
            $requestDocument,
            $sessionId,
            ['keyId' => $keyId]
        );
        if (!$sendResult->isSent()) {
            return [false, null];
        }

        return [true, $sendResult->getImportRequest()->receiverRequestId];
    }

    private static function createBicryptId(SBBOLCustomerKeyOwner $keyOwner)
    {
        $customer = $keyOwner->customer;
        $certAuthId = $customer->certAuthId;
        $certNumberHex = strtoupper(dechex($customer->lastCertNumber + 1));

        $ownerNameParts = preg_split('/\s+/', $keyOwner->fullName);
        $ownerLastName = $ownerNameParts[0] ?? '';
        $ownerInitials = array_slice($ownerNameParts, 1);
        $ownerId = $ownerLastName
            . implode(
                '',
                array_map(
                    function ($item) { return mb_substr($item, 0, 1); },
                    $ownerInitials
                )
            );

        return $certAuthId
            . str_pad($certNumberHex, 8 - strlen($certAuthId), '0', STR_PAD_LEFT)
            . 's'
            . $ownerId;
    }

    /** @inheritdoc */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $this->certificateRequestFile = UploadedFile::getInstance($this, 'certificateRequestFile');
        $this->keyContainerZipFile = UploadedFile::getInstance($this, 'keyContainerZipFile');
        $this->publicKeyFile = UploadedFile::getInstance($this, 'publicKeyFile');

        return $result;
    }
}
