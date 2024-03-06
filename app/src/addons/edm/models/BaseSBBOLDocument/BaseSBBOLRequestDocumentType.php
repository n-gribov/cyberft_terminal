<?php

namespace addons\edm\models\BaseSBBOLDocument;

use common\base\BaseType;
use common\base\interfaces\SignableType;
use common\helpers\sbbol\SBBOLDocumentDigestBuilder;
use common\helpers\sbbol\SBBOLDocumentHelper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\request\DigitalSignType;
use common\models\sbbolxml\request\Request;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

abstract class BaseSBBOLRequestDocumentType extends BaseType implements SignableType
{
    const TYPE = null;
    const SBBOL_DOCUMENT_TYPE = null;

    /** @var Request */
    public $request;

    public $digest;

    /** @var SimpleXMLElement */
    protected $xmlDom;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['request', 'digest'], 'safe'],
                [['request'], 'required'],
            ]
        );
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        $this->request = SBBOLXmlSerializer::deserialize($data, Request::class);
        return $this;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getSignaturesList()
    {
        return array_map(
            function (DigitalSignType $signature) {
                return [
                    'fingerprint' => null,
                    'name'        => $signature->getSN(),
                    'email'       => null,
                    'phone'       => null,
                    'post'        => null,
                    'role'        => Cert::ROLE_SIGNER,
                    'signingTime' => null,
                ];
            },
            $this->request->getSign()
        );
    }

    public function getModelDataAsString()
    {
        return SBBOLXmlSerializer::serialize($this->request);
    }

    public function getSignedInfo(?string $signerCertificate = null)
    {
        return SBBOLDocumentDigestBuilder::build($this->getModelDataAsString(), static::SBBOL_DOCUMENT_TYPE);
    }

    public function injectSignature($signatureValue, $certBody)
    {
        if (empty($signatureValue)) {
            \Yii::info('Got empty signature value');
            return false;
        }

        if (X509FileModel::isCertificate($certBody)) {
            $certificateX509 = X509FileModel::loadData($certBody);
        } else {
            \Yii::info("Got invalid certificate body: $certBody");
            return false;
        }

        $issuer = SBBOLHelper::createCertificateIssuerString($certificateX509);
        $signature = (new DigitalSignType())
            ->setValue($signatureValue)
            ->setSN($certificateX509->getSerialNumber())
            ->setIssuer($issuer);

        $this->request->addToSign($signature);

        if (empty($this->digest)) {
            $this->createDigest();
        }

        return true;
    }

    private static function formatCertificateSerial($decimalSerial)
    {
        $hexSerial = gmp_strval(gmp_init($decimalSerial, 10), 16);
        if (strlen($hexSerial) % 2 === 1) {
            $hexSerial = "0$hexSerial";
        }
        return strtoupper($hexSerial);
    }

    private function createDigest()
    {
        $xml = SBBOLXmlSerializer::serialize($this->request);
        $documentType = SBBOLDocumentHelper::detectRequestDocumentType($this->request);
        $this->digest = SBBOLDocumentDigestBuilder::build($xml, $documentType);
    }
}
