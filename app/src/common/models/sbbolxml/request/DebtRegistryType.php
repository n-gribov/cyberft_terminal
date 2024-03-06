<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DebtRegistryType
 *
 * Реестр задолженностей
 * XSD Type: DebtRegistry
 */
class DebtRegistryType extends DocBaseType
{

    /**
     * Реквизиты реестра задолженностей
     *
     * @property \common\models\sbbolxml\request\DebtRegistryDataType $debtRegistryData
     */
    private $debtRegistryData = null;

    /**
     * Количество записей
     *
     * @property integer $recordNum
     */
    private $recordNum = null;

    /**
     * Наменование EPSID
     *
     * @property string $ePSIDName
     */
    private $ePSIDName = null;

    /**
     * Данные о файлах реестра задолженностей
     *
     * @property \common\models\sbbolxml\request\EssenceAttachmentsType $essenceAttachments
     */
    private $essenceAttachments = null;

    /**
     * Gets as debtRegistryData
     *
     * Реквизиты реестра задолженностей
     *
     * @return \common\models\sbbolxml\request\DebtRegistryDataType
     */
    public function getDebtRegistryData()
    {
        return $this->debtRegistryData;
    }

    /**
     * Sets a new debtRegistryData
     *
     * Реквизиты реестра задолженностей
     *
     * @param \common\models\sbbolxml\request\DebtRegistryDataType $debtRegistryData
     * @return static
     */
    public function setDebtRegistryData(\common\models\sbbolxml\request\DebtRegistryDataType $debtRegistryData)
    {
        $this->debtRegistryData = $debtRegistryData;
        return $this;
    }

    /**
     * Gets as recordNum
     *
     * Количество записей
     *
     * @return integer
     */
    public function getRecordNum()
    {
        return $this->recordNum;
    }

    /**
     * Sets a new recordNum
     *
     * Количество записей
     *
     * @param integer $recordNum
     * @return static
     */
    public function setRecordNum($recordNum)
    {
        $this->recordNum = $recordNum;
        return $this;
    }

    /**
     * Gets as ePSIDName
     *
     * Наменование EPSID
     *
     * @return string
     */
    public function getEPSIDName()
    {
        return $this->ePSIDName;
    }

    /**
     * Sets a new ePSIDName
     *
     * Наменование EPSID
     *
     * @param string $ePSIDName
     * @return static
     */
    public function setEPSIDName($ePSIDName)
    {
        $this->ePSIDName = $ePSIDName;
        return $this;
    }

    /**
     * Gets as essenceAttachments
     *
     * Данные о файлах реестра задолженностей
     *
     * @return \common\models\sbbolxml\request\EssenceAttachmentsType
     */
    public function getEssenceAttachments()
    {
        return $this->essenceAttachments;
    }

    /**
     * Sets a new essenceAttachments
     *
     * Данные о файлах реестра задолженностей
     *
     * @param \common\models\sbbolxml\request\EssenceAttachmentsType $essenceAttachments
     * @return static
     */
    public function setEssenceAttachments(\common\models\sbbolxml\request\EssenceAttachmentsType $essenceAttachments)
    {
        $this->essenceAttachments = $essenceAttachments;
        return $this;
    }


}

