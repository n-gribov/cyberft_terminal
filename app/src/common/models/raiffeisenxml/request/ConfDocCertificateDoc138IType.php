<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ConfDocCertificateDoc138IType
 *
 *
 * XSD Type: ConfDocCertificateDoc138I
 */
class ConfDocCertificateDoc138IType
{

    /**
     * Порядковый номер строки в справке
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Номер и Дата подтверждающего документа
     *
     * @property \common\models\raiffeisenxml\request\ConfDocType $confDoc
     */
    private $confDoc = null;

    /**
     * Код вида ПД
     *
     * @property string $docCode
     */
    private $docCode = null;

    /**
     * Сумма в ед. валюты документа
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Сумма в ед. валюты контракта
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $contrSum
     */
    private $contrSum = null;

    /**
     * Признак поставки
     *
     * @property string $delDir
     */
    private $delDir = null;

    /**
     * Сумма, соответствующая признаку поставки 2 или 3
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $delDirSum
     */
    private $delDirSum = null;

    /**
     * Сумма, соответствующая признаку поставки 2 или 3, в единицах валюты контракта
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $delDirSumContr
     */
    private $delDirSumContr = null;

    /**
     * Ожидаемый срок
     *
     * @property \DateTime $term
     */
    private $term = null;

    /**
     * Страна грузоотправителя (грузополучателя)
     *
     * @property \common\models\raiffeisenxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @property \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as strNum
     *
     * Порядковый номер строки в справке
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Порядковый номер строки в справке
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as confDoc
     *
     * Номер и Дата подтверждающего документа
     *
     * @return \common\models\raiffeisenxml\request\ConfDocType
     */
    public function getConfDoc()
    {
        return $this->confDoc;
    }

    /**
     * Sets a new confDoc
     *
     * Номер и Дата подтверждающего документа
     *
     * @param \common\models\raiffeisenxml\request\ConfDocType $confDoc
     * @return static
     */
    public function setConfDoc(\common\models\raiffeisenxml\request\ConfDocType $confDoc)
    {
        $this->confDoc = $confDoc;
        return $this;
    }

    /**
     * Gets as docCode
     *
     * Код вида ПД
     *
     * @return string
     */
    public function getDocCode()
    {
        return $this->docCode;
    }

    /**
     * Sets a new docCode
     *
     * Код вида ПД
     *
     * @param string $docCode
     * @return static
     */
    public function setDocCode($docCode)
    {
        $this->docCode = $docCode;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма в ед. валюты документа
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма в ед. валюты документа
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $docSum
     * @return static
     */
    public function setDocSum(\common\models\raiffeisenxml\request\CurrAmountType $docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as contrSum
     *
     * Сумма в ед. валюты контракта
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getContrSum()
    {
        return $this->contrSum;
    }

    /**
     * Sets a new contrSum
     *
     * Сумма в ед. валюты контракта
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $contrSum
     * @return static
     */
    public function setContrSum(\common\models\raiffeisenxml\request\CurrAmountType $contrSum)
    {
        $this->contrSum = $contrSum;
        return $this;
    }

    /**
     * Gets as delDir
     *
     * Признак поставки
     *
     * @return string
     */
    public function getDelDir()
    {
        return $this->delDir;
    }

    /**
     * Sets a new delDir
     *
     * Признак поставки
     *
     * @param string $delDir
     * @return static
     */
    public function setDelDir($delDir)
    {
        $this->delDir = $delDir;
        return $this;
    }

    /**
     * Gets as delDirSum
     *
     * Сумма, соответствующая признаку поставки 2 или 3
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getDelDirSum()
    {
        return $this->delDirSum;
    }

    /**
     * Sets a new delDirSum
     *
     * Сумма, соответствующая признаку поставки 2 или 3
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $delDirSum
     * @return static
     */
    public function setDelDirSum(\common\models\raiffeisenxml\request\CurrAmountType $delDirSum)
    {
        $this->delDirSum = $delDirSum;
        return $this;
    }

    /**
     * Gets as delDirSumContr
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в единицах валюты контракта
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getDelDirSumContr()
    {
        return $this->delDirSumContr;
    }

    /**
     * Sets a new delDirSumContr
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в единицах валюты контракта
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $delDirSumContr
     * @return static
     */
    public function setDelDirSumContr(\common\models\raiffeisenxml\request\CurrAmountType $delDirSumContr)
    {
        $this->delDirSumContr = $delDirSumContr;
        return $this;
    }

    /**
     * Gets as term
     *
     * Ожидаемый срок
     *
     * @return \DateTime
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Sets a new term
     *
     * Ожидаемый срок
     *
     * @param \DateTime $term
     * @return static
     */
    public function setTerm(\DateTime $term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as country
     *
     * Страна грузоотправителя (грузополучателя)
     *
     * @return \common\models\raiffeisenxml\request\CountryType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Страна грузоотправителя (грузополучателя)
     *
     * @param \common\models\raiffeisenxml\request\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @return \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об
     *  обязательной продаже, уведомление и
     *  т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Примечание
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Примечание
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

