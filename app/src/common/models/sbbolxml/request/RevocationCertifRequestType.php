<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RevocationCertifRequestType
 *
 *
 * XSD Type: RevocationCertifRequest
 */
class RevocationCertifRequestType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\RevocationCertifRequestType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Данные организации
     *
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Уникальный идентификатор средства подписи, для которого производится отзыв
     *  активного сертификата.
     *
     * @property string $idCrypto
     */
    private $idCrypto = null;

    /**
     * Причина отзыва
     *
     * @property string $reason
     */
    private $reason = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\RevocationCertifRequestType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\sbbolxml\request\RevocationCertifRequestType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\RevocationCertifRequestType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Данные организации
     *
     * @return \common\models\sbbolxml\request\OrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Данные организации
     *
     * @param \common\models\sbbolxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as idCrypto
     *
     * Уникальный идентификатор средства подписи, для которого производится отзыв
     *  активного сертификата.
     *
     * @return string
     */
    public function getIdCrypto()
    {
        return $this->idCrypto;
    }

    /**
     * Sets a new idCrypto
     *
     * Уникальный идентификатор средства подписи, для которого производится отзыв
     *  активного сертификата.
     *
     * @param string $idCrypto
     * @return static
     */
    public function setIdCrypto($idCrypto)
    {
        $this->idCrypto = $idCrypto;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Причина отзыва
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Причина отзыва
     *
     * @param string $reason
     * @return static
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }


}

