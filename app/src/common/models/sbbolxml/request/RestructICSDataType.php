<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RestructICSDataType
 *
 * Сведения о переоформляемом ПС
 * XSD Type: RestructICSData
 */
class RestructICSDataType
{

    /**
     * Идентификатор изменяемой ВБК на банке
     *
     * @property string $iCSDocId
     */
    private $iCSDocId = null;

    /**
     * Номер контракта
     *
     * @property string $iCSNum
     */
    private $iCSNum = null;

    /**
     * Дата ПС в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $iCSDate
     */
    private $iCSDate = null;

    /**
     * Признак наличия изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @property boolean $changeInUSRLE
     */
    private $changeInUSRLE = null;

    /**
     * Содержание изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @property string $changedContentInfo
     */
    private $changedContentInfo = null;

    /**
     * Признак пролонгации по условиям контракта, кредитного договора
     *
     * @property boolean $prolongation
     */
    private $prolongation = null;

    /**
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @property \common\models\sbbolxml\request\GroundInfoICSType $groundInfo
     */
    private $groundInfo = null;

    /**
     * Сведения о переоформляемых разделах/подразделах
     *
     * @property \common\models\sbbolxml\request\TableDealPassIcsType $tableDealPassIcs
     */
    private $tableDealPassIcs = null;

    /**
     * Gets as iCSDocId
     *
     * Идентификатор изменяемой ВБК на банке
     *
     * @return string
     */
    public function getICSDocId()
    {
        return $this->iCSDocId;
    }

    /**
     * Sets a new iCSDocId
     *
     * Идентификатор изменяемой ВБК на банке
     *
     * @param string $iCSDocId
     * @return static
     */
    public function setICSDocId($iCSDocId)
    {
        $this->iCSDocId = $iCSDocId;
        return $this;
    }

    /**
     * Gets as iCSNum
     *
     * Номер контракта
     *
     * @return string
     */
    public function getICSNum()
    {
        return $this->iCSNum;
    }

    /**
     * Sets a new iCSNum
     *
     * Номер контракта
     *
     * @param string $iCSNum
     * @return static
     */
    public function setICSNum($iCSNum)
    {
        $this->iCSNum = $iCSNum;
        return $this;
    }

    /**
     * Gets as iCSDate
     *
     * Дата ПС в формате ДД.ММ.ГГГГ
     *
     * @return \DateTime
     */
    public function getICSDate()
    {
        return $this->iCSDate;
    }

    /**
     * Sets a new iCSDate
     *
     * Дата ПС в формате ДД.ММ.ГГГГ
     *
     * @param \DateTime $iCSDate
     * @return static
     */
    public function setICSDate(\DateTime $iCSDate)
    {
        $this->iCSDate = $iCSDate;
        return $this;
    }

    /**
     * Gets as changeInUSRLE
     *
     * Признак наличия изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @return boolean
     */
    public function getChangeInUSRLE()
    {
        return $this->changeInUSRLE;
    }

    /**
     * Sets a new changeInUSRLE
     *
     * Признак наличия изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @param boolean $changeInUSRLE
     * @return static
     */
    public function setChangeInUSRLE($changeInUSRLE)
    {
        $this->changeInUSRLE = $changeInUSRLE;
        return $this;
    }

    /**
     * Gets as changedContentInfo
     *
     * Содержание изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @return string
     */
    public function getChangedContentInfo()
    {
        return $this->changedContentInfo;
    }

    /**
     * Sets a new changedContentInfo
     *
     * Содержание изменений в связи с изменениями в ЕГРЮЛ/ЕГРИП/реестре
     *  нотариусов/реестре адвокатов
     *
     * @param string $changedContentInfo
     * @return static
     */
    public function setChangedContentInfo($changedContentInfo)
    {
        $this->changedContentInfo = $changedContentInfo;
        return $this;
    }

    /**
     * Gets as prolongation
     *
     * Признак пролонгации по условиям контракта, кредитного договора
     *
     * @return boolean
     */
    public function getProlongation()
    {
        return $this->prolongation;
    }

    /**
     * Sets a new prolongation
     *
     * Признак пролонгации по условиям контракта, кредитного договора
     *
     * @param boolean $prolongation
     * @return static
     */
    public function setProlongation($prolongation)
    {
        $this->prolongation = $prolongation;
        return $this;
    }

    /**
     * Gets as groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @return \common\models\sbbolxml\request\GroundInfoICSType
     */
    public function getGroundInfo()
    {
        return $this->groundInfo;
    }

    /**
     * Sets a new groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @param \common\models\sbbolxml\request\GroundInfoICSType $groundInfo
     * @return static
     */
    public function setGroundInfo(\common\models\sbbolxml\request\GroundInfoICSType $groundInfo)
    {
        $this->groundInfo = $groundInfo;
        return $this;
    }

    /**
     * Gets as tableDealPassIcs
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @return \common\models\sbbolxml\request\TableDealPassIcsType
     */
    public function getTableDealPassIcs()
    {
        return $this->tableDealPassIcs;
    }

    /**
     * Sets a new tableDealPassIcs
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @param \common\models\sbbolxml\request\TableDealPassIcsType $tableDealPassIcs
     * @return static
     */
    public function setTableDealPassIcs(\common\models\sbbolxml\request\TableDealPassIcsType $tableDealPassIcs)
    {
        $this->tableDealPassIcs = $tableDealPassIcs;
        return $this;
    }


}

