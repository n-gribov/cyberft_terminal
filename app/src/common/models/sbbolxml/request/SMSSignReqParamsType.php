<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SMSSignReqParamsType
 *
 * Параметры запроса на подпись с помощью SMS
 * XSD Type: SMSSignReqParams
 */
class SMSSignReqParamsType
{

    /**
     * Идентификатор подписываемого объекта (документа или записи) в СББОЛ
     *
     * @property string $objId
     */
    private $objId = null;

    /**
     * Идентификатор подписываемого объекта (документа или записи) во внешней системе
     *
     * @property string $objExtId
     */
    private $objExtId = null;

    /**
     * Тип подписываемого документа (при подписывании документа)
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Наименование справочника (при подписывании записи справочника)
     *
     * @property string $dictName
     */
    private $dictName = null;

    /**
     * Номер записи в документе
     *  (для зарплатной ведомости используется атрибут Request/SalaryDoc/TransfInfo/Transf/@numSt)
     *
     * @property integer $numSt
     */
    private $numSt = null;

    /**
     * Gets as objId
     *
     * Идентификатор подписываемого объекта (документа или записи) в СББОЛ
     *
     * @return string
     */
    public function getObjId()
    {
        return $this->objId;
    }

    /**
     * Sets a new objId
     *
     * Идентификатор подписываемого объекта (документа или записи) в СББОЛ
     *
     * @param string $objId
     * @return static
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;
        return $this;
    }

    /**
     * Gets as objExtId
     *
     * Идентификатор подписываемого объекта (документа или записи) во внешней системе
     *
     * @return string
     */
    public function getObjExtId()
    {
        return $this->objExtId;
    }

    /**
     * Sets a new objExtId
     *
     * Идентификатор подписываемого объекта (документа или записи) во внешней системе
     *
     * @param string $objExtId
     * @return static
     */
    public function setObjExtId($objExtId)
    {
        $this->objExtId = $objExtId;
        return $this;
    }

    /**
     * Gets as docType
     *
     * Тип подписываемого документа (при подписывании документа)
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * Тип подписываемого документа (при подписывании документа)
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }

    /**
     * Gets as dictName
     *
     * Наименование справочника (при подписывании записи справочника)
     *
     * @return string
     */
    public function getDictName()
    {
        return $this->dictName;
    }

    /**
     * Sets a new dictName
     *
     * Наименование справочника (при подписывании записи справочника)
     *
     * @param string $dictName
     * @return static
     */
    public function setDictName($dictName)
    {
        $this->dictName = $dictName;
        return $this;
    }

    /**
     * Gets as numSt
     *
     * Номер записи в документе
     *  (для зарплатной ведомости используется атрибут Request/SalaryDoc/TransfInfo/Transf/@numSt)
     *
     * @return integer
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер записи в документе
     *  (для зарплатной ведомости используется атрибут Request/SalaryDoc/TransfInfo/Transf/@numSt)
     *
     * @param integer $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }


}

