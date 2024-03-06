<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing SIInfoType
 *
 * ПИ по сделке
 * XSD Type: SIInfoType
 */
class SIInfoType
{

    /**
     * Идентификатор ПИ в DD
     *
     * @property string $sIID
     */
    private $sIID = null;

    /**
     * Признак ПИ, привязанной под сделку:
     *  false - ПИ не является стандартной,
     *  true - ПИ является стандартной (SSI)
     *
     * @property boolean $isSSI
     */
    private $isSSI = null;

    /**
     * Наименование ПИ
     *
     * @property string $sIName
     */
    private $sIName = null;

    /**
     * Полное наименование ПИ
     *
     * @property string $sIFullName
     */
    private $sIFullName = null;

    /**
     * Банковские реквизиты стороны Контрагента
     *
     * @property string $correspondentBankDetails
     */
    private $correspondentBankDetails = null;

    /**
     * Банковские реквизиты стороны Сбербанка
     *
     * @property string $bankDetails
     */
    private $bankDetails = null;

    /**
     * Валюта ПИ
     *
     * @property string $sICcy
     */
    private $sICcy = null;

    /**
     * Тип ПИ:
     *  FX - валютная ПИ,
     *  RU - рублевая ПИ,
     *  FS - ПИ для мягких валют
     *
     * @property string $sIType
     */
    private $sIType = null;

    /**
     * Счет клиента
     *
     * @property string $sIAcc
     */
    private $sIAcc = null;

    /**
     * Наименование клиента
     *
     * @property string $cptyName
     */
    private $cptyName = null;

    /**
     * Счет-корреспондент
     *
     * @property string $sICorAcc
     */
    private $sICorAcc = null;

    /**
     * Наименование банка
     *
     * @property string $sIBankName
     */
    private $sIBankName = null;

    /**
     * SWIFT код банка
     *
     * @property string $sIBankSWIFT
     */
    private $sIBankSWIFT = null;

    /**
     * Наименование корреспондентского банка
     *
     * @property string $sICorBankName
     */
    private $sICorBankName = null;

    /**
     * SWIFT код корреспондентского банка
     *
     * @property string $sICorBankSWIFT
     */
    private $sICorBankSWIFT = null;

    /**
     * Gets as sIID
     *
     * Идентификатор ПИ в DD
     *
     * @return string
     */
    public function getSIID()
    {
        return $this->sIID;
    }

    /**
     * Sets a new sIID
     *
     * Идентификатор ПИ в DD
     *
     * @param string $sIID
     * @return static
     */
    public function setSIID($sIID)
    {
        $this->sIID = $sIID;
        return $this;
    }

    /**
     * Gets as isSSI
     *
     * Признак ПИ, привязанной под сделку:
     *  false - ПИ не является стандартной,
     *  true - ПИ является стандартной (SSI)
     *
     * @return boolean
     */
    public function getIsSSI()
    {
        return $this->isSSI;
    }

    /**
     * Sets a new isSSI
     *
     * Признак ПИ, привязанной под сделку:
     *  false - ПИ не является стандартной,
     *  true - ПИ является стандартной (SSI)
     *
     * @param boolean $isSSI
     * @return static
     */
    public function setIsSSI($isSSI)
    {
        $this->isSSI = $isSSI;
        return $this;
    }

    /**
     * Gets as sIName
     *
     * Наименование ПИ
     *
     * @return string
     */
    public function getSIName()
    {
        return $this->sIName;
    }

    /**
     * Sets a new sIName
     *
     * Наименование ПИ
     *
     * @param string $sIName
     * @return static
     */
    public function setSIName($sIName)
    {
        $this->sIName = $sIName;
        return $this;
    }

    /**
     * Gets as sIFullName
     *
     * Полное наименование ПИ
     *
     * @return string
     */
    public function getSIFullName()
    {
        return $this->sIFullName;
    }

    /**
     * Sets a new sIFullName
     *
     * Полное наименование ПИ
     *
     * @param string $sIFullName
     * @return static
     */
    public function setSIFullName($sIFullName)
    {
        $this->sIFullName = $sIFullName;
        return $this;
    }

    /**
     * Gets as correspondentBankDetails
     *
     * Банковские реквизиты стороны Контрагента
     *
     * @return string
     */
    public function getCorrespondentBankDetails()
    {
        return $this->correspondentBankDetails;
    }

    /**
     * Sets a new correspondentBankDetails
     *
     * Банковские реквизиты стороны Контрагента
     *
     * @param string $correspondentBankDetails
     * @return static
     */
    public function setCorrespondentBankDetails($correspondentBankDetails)
    {
        $this->correspondentBankDetails = $correspondentBankDetails;
        return $this;
    }

    /**
     * Gets as bankDetails
     *
     * Банковские реквизиты стороны Сбербанка
     *
     * @return string
     */
    public function getBankDetails()
    {
        return $this->bankDetails;
    }

    /**
     * Sets a new bankDetails
     *
     * Банковские реквизиты стороны Сбербанка
     *
     * @param string $bankDetails
     * @return static
     */
    public function setBankDetails($bankDetails)
    {
        $this->bankDetails = $bankDetails;
        return $this;
    }

    /**
     * Gets as sICcy
     *
     * Валюта ПИ
     *
     * @return string
     */
    public function getSICcy()
    {
        return $this->sICcy;
    }

    /**
     * Sets a new sICcy
     *
     * Валюта ПИ
     *
     * @param string $sICcy
     * @return static
     */
    public function setSICcy($sICcy)
    {
        $this->sICcy = $sICcy;
        return $this;
    }

    /**
     * Gets as sIType
     *
     * Тип ПИ:
     *  FX - валютная ПИ,
     *  RU - рублевая ПИ,
     *  FS - ПИ для мягких валют
     *
     * @return string
     */
    public function getSIType()
    {
        return $this->sIType;
    }

    /**
     * Sets a new sIType
     *
     * Тип ПИ:
     *  FX - валютная ПИ,
     *  RU - рублевая ПИ,
     *  FS - ПИ для мягких валют
     *
     * @param string $sIType
     * @return static
     */
    public function setSIType($sIType)
    {
        $this->sIType = $sIType;
        return $this;
    }

    /**
     * Gets as sIAcc
     *
     * Счет клиента
     *
     * @return string
     */
    public function getSIAcc()
    {
        return $this->sIAcc;
    }

    /**
     * Sets a new sIAcc
     *
     * Счет клиента
     *
     * @param string $sIAcc
     * @return static
     */
    public function setSIAcc($sIAcc)
    {
        $this->sIAcc = $sIAcc;
        return $this;
    }

    /**
     * Gets as cptyName
     *
     * Наименование клиента
     *
     * @return string
     */
    public function getCptyName()
    {
        return $this->cptyName;
    }

    /**
     * Sets a new cptyName
     *
     * Наименование клиента
     *
     * @param string $cptyName
     * @return static
     */
    public function setCptyName($cptyName)
    {
        $this->cptyName = $cptyName;
        return $this;
    }

    /**
     * Gets as sICorAcc
     *
     * Счет-корреспондент
     *
     * @return string
     */
    public function getSICorAcc()
    {
        return $this->sICorAcc;
    }

    /**
     * Sets a new sICorAcc
     *
     * Счет-корреспондент
     *
     * @param string $sICorAcc
     * @return static
     */
    public function setSICorAcc($sICorAcc)
    {
        $this->sICorAcc = $sICorAcc;
        return $this;
    }

    /**
     * Gets as sIBankName
     *
     * Наименование банка
     *
     * @return string
     */
    public function getSIBankName()
    {
        return $this->sIBankName;
    }

    /**
     * Sets a new sIBankName
     *
     * Наименование банка
     *
     * @param string $sIBankName
     * @return static
     */
    public function setSIBankName($sIBankName)
    {
        $this->sIBankName = $sIBankName;
        return $this;
    }

    /**
     * Gets as sIBankSWIFT
     *
     * SWIFT код банка
     *
     * @return string
     */
    public function getSIBankSWIFT()
    {
        return $this->sIBankSWIFT;
    }

    /**
     * Sets a new sIBankSWIFT
     *
     * SWIFT код банка
     *
     * @param string $sIBankSWIFT
     * @return static
     */
    public function setSIBankSWIFT($sIBankSWIFT)
    {
        $this->sIBankSWIFT = $sIBankSWIFT;
        return $this;
    }

    /**
     * Gets as sICorBankName
     *
     * Наименование корреспондентского банка
     *
     * @return string
     */
    public function getSICorBankName()
    {
        return $this->sICorBankName;
    }

    /**
     * Sets a new sICorBankName
     *
     * Наименование корреспондентского банка
     *
     * @param string $sICorBankName
     * @return static
     */
    public function setSICorBankName($sICorBankName)
    {
        $this->sICorBankName = $sICorBankName;
        return $this;
    }

    /**
     * Gets as sICorBankSWIFT
     *
     * SWIFT код корреспондентского банка
     *
     * @return string
     */
    public function getSICorBankSWIFT()
    {
        return $this->sICorBankSWIFT;
    }

    /**
     * Sets a new sICorBankSWIFT
     *
     * SWIFT код корреспондентского банка
     *
     * @param string $sICorBankSWIFT
     * @return static
     */
    public function setSICorBankSWIFT($sICorBankSWIFT)
    {
        $this->sICorBankSWIFT = $sICorBankSWIFT;
        return $this;
    }


}

