<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmCashierType
 *
 * Запись вносителя
 * XSD Type: AdmCashier
 */
class AdmCashierType
{

    /**
     * Признак того, что вноситель подписан
     *  0 - не подписан, 1 - подписан
     *
     * @property boolean $signed
     */
    private $signed = null;

    /**
     * Признак того, что вноситель был подписан хотя бы 1 раз
     *  0 - не подписан, 1 - подписан
     *
     * @property boolean $wasSigned
     */
    private $wasSigned = null;

    /**
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Номер заявления
     *
     * @property integer $docNum
     */
    private $docNum = null;

    /**
     * Дата заявления
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * @property \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType $cashierInfo
     */
    private $cashierInfo = null;

    /**
     * Информация по доверенности
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\EmpowermentAType $empowerment
     */
    private $empowerment = null;

    /**
     * Информация об устройстве внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\DeviceInfoAType $deviceInfo
     */
    private $deviceInfo = null;

    /**
     * Шаблоны внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[] $aDMTemplates
     */
    private $aDMTemplates = null;

    /**
     * Gets as signed
     *
     * Признак того, что вноситель подписан
     *  0 - не подписан, 1 - подписан
     *
     * @return boolean
     */
    public function getSigned()
    {
        return $this->signed;
    }

    /**
     * Sets a new signed
     *
     * Признак того, что вноситель подписан
     *  0 - не подписан, 1 - подписан
     *
     * @param boolean $signed
     * @return static
     */
    public function setSigned($signed)
    {
        $this->signed = $signed;
        return $this;
    }

    /**
     * Gets as wasSigned
     *
     * Признак того, что вноситель был подписан хотя бы 1 раз
     *  0 - не подписан, 1 - подписан
     *
     * @return boolean
     */
    public function getWasSigned()
    {
        return $this->wasSigned;
    }

    /**
     * Sets a new wasSigned
     *
     * Признак того, что вноситель был подписан хотя бы 1 раз
     *  0 - не подписан, 1 - подписан
     *
     * @param boolean $wasSigned
     * @return static
     */
    public function setWasSigned($wasSigned)
    {
        $this->wasSigned = $wasSigned;
        return $this;
    }

    /**
     * Gets as docId
     *
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер заявления
     *
     * @return integer
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер заявления
     *
     * @param integer $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата заявления
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата заявления
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as cashierInfo
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType
     */
    public function getCashierInfo()
    {
        return $this->cashierInfo;
    }

    /**
     * Sets a new cashierInfo
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType $cashierInfo
     * @return static
     */
    public function setCashierInfo(\common\models\sbbolxml\response\AdmCashierType\CashierInfoAType $cashierInfo)
    {
        $this->cashierInfo = $cashierInfo;
        return $this;
    }

    /**
     * Gets as empowerment
     *
     * Информация по доверенности
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\EmpowermentAType
     */
    public function getEmpowerment()
    {
        return $this->empowerment;
    }

    /**
     * Sets a new empowerment
     *
     * Информация по доверенности
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\EmpowermentAType $empowerment
     * @return static
     */
    public function setEmpowerment(\common\models\sbbolxml\response\AdmCashierType\EmpowermentAType $empowerment)
    {
        $this->empowerment = $empowerment;
        return $this;
    }

    /**
     * Gets as deviceInfo
     *
     * Информация об устройстве внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\DeviceInfoAType
     */
    public function getDeviceInfo()
    {
        return $this->deviceInfo;
    }

    /**
     * Sets a new deviceInfo
     *
     * Информация об устройстве внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\DeviceInfoAType $deviceInfo
     * @return static
     */
    public function setDeviceInfo(\common\models\sbbolxml\response\AdmCashierType\DeviceInfoAType $deviceInfo)
    {
        $this->deviceInfo = $deviceInfo;
        return $this;
    }

    /**
     * Adds as template
     *
     * Шаблоны внесения средств
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType $template
     */
    public function addToADMTemplates(\common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType $template)
    {
        $this->aDMTemplates[] = $template;
        return $this;
    }

    /**
     * isset aDMTemplates
     *
     * Шаблоны внесения средств
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetADMTemplates($index)
    {
        return isset($this->aDMTemplates[$index]);
    }

    /**
     * unset aDMTemplates
     *
     * Шаблоны внесения средств
     *
     * @param scalar $index
     * @return void
     */
    public function unsetADMTemplates($index)
    {
        unset($this->aDMTemplates[$index]);
    }

    /**
     * Gets as aDMTemplates
     *
     * Шаблоны внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[]
     */
    public function getADMTemplates()
    {
        return $this->aDMTemplates;
    }

    /**
     * Sets a new aDMTemplates
     *
     * Шаблоны внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[] $aDMTemplates
     * @return static
     */
    public function setADMTemplates(array $aDMTemplates)
    {
        $this->aDMTemplates = $aDMTemplates;
        return $this;
    }


}

