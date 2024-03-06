<?php

namespace addons\edm\models\BaseVTBDocument;

use common\base\interfaces\SignableType;
use common\base\BaseType;
use common\helpers\vtb\BSDocumentSignedDataBuilder;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\service\SignInfo;
use common\models\vtbxml\service\SignInfoSignature;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

abstract class BaseVTBDocumentType extends BaseType implements SignableType
{
    const TYPE = null;
    const VTB_DOCUMENT_CLASS = null;

    /** @var integer */
    public $customerId;

    /** @var BSDocument */
    public $document;

    /** @var integer */
    public $documentVersion;

    /** @var SignInfo */
    public $signatureInfo;

    /** @var SimpleXMLElement */
    protected $xmlDom;

    public function rules()
    {
        $allAttributes = array_values($this->attributes());
        return ArrayHelper::merge(
            parent::rules(),
            [
                [$allAttributes, 'safe'],
                [$allAttributes, 'required'],
            ]
        );
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        $this->xmlDom = new SimpleXMLElement($data);
        $this->parseXml();
        return $this;
    }

    private function parseXml()
    {
        foreach ($this->attributes() as $attribute) {
            $this->$attribute = $this->xmlDom->$attribute;
        }
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
            function (SignInfoSignature $signature) {
                return [
                    'fingerprint' => null,
                    'name'        => $signature->uid,
                    'email'       => null,
                    'phone'       => null,
                    'post'        => null,
                    'role'        => Cert::ROLE_SIGNER,
                    'signingTime' => null,
                ];
            },
            $this->signatureInfo === null ? [] : $this->signatureInfo->signatures
        );
    }

    public function getModelDataAsString()
    {
        return $this->document->toXml($this->documentVersion);
    }

    public function getSignedInfo(?string $signerCertificate = null)
    {
        return BSDocumentSignedDataBuilder::build($this->document, $this->documentVersion);
    }

    public function injectSignature($signatureValue, $certBody)
    {
        if (empty($signatureValue)) {
            \Yii::info('Got empty signature value');
            return false;
        }

        $certCN = null;
        if (X509FileModel::isCertificate($certBody)) {
            $x509Info = X509FileModel::loadData($certBody);
            $certCN = $x509Info->subject['CN'];
        } else {
            \Yii::info("Got invalid certificate body: $certBody");
            return false;
        }
        $this->signatureInfo->signatures[] = new SignInfoSignature([
            'value'      => $signatureValue,
            'number'     => count($this->signatureInfo->signatures) + 1,
            'uid'        => $certCN,
            'cryptLibId' => 8, // MS Crypto API 2.0
        ]);

        return true;
    }
}
