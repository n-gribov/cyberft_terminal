<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AdmCashierType
 *
 * Запрос на добавление/редактирование документа Вносители средств
 * XSD Type: AdmCashier
 */
class AdmCashierType
{

    /**
     * Версия документа
     *
     * @property string $docVersion
     */
    private $docVersion = null;

    /**
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Номер заявления
     *  Заполняется при редактировании вносителя,
     *  подставить значение из AdmCashiers/AdmCashier/@docNum
     *  Версия 1
     *
     * @property integer $docNum
     */
    private $docNum = null;

    /**
     * Дата заявления
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @property string $genSMSSign
     */
    private $genSMSSign = null;

    /**
     * @property \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType $cashierInfo
     */
    private $cashierInfo = null;

    /**
     * Информация о полномочиях
     *  Группа полей доступна для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @property \common\models\sbbolxml\request\AdmCashierType\EmpowermentAType $empowerment
     */
    private $empowerment = null;

    /**
     * Информация об устройстве внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmCashierType\DeviceInfoAType $deviceInfo
     */
    private $deviceInfo = null;

    /**
     * Шаблоны внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType\TemplateAType[] $aDMTemplates
     */
    private $aDMTemplates = null;

    /**
     * Gets as docVersion
     *
     * Версия документа
     *
     * @return string
     */
    public function getDocVersion()
    {
        return $this->docVersion;
    }

    /**
     * Sets a new docVersion
     *
     * Версия документа
     *
     * @param string $docVersion
     * @return static
     */
    public function setDocVersion($docVersion)
    {
        $this->docVersion = $docVersion;
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
     * Gets as docExtId
     *
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер заявления
     *  Заполняется при редактировании вносителя,
     *  подставить значение из AdmCashiers/AdmCashier/@docNum
     *  Версия 1
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
     *  Заполняется при редактировании вносителя,
     *  подставить значение из AdmCashiers/AdmCashier/@docNum
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Gets as genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @return string
     */
    public function getGenSMSSign()
    {
        return $this->genSMSSign;
    }

    /**
     * Sets a new genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @param string $genSMSSign
     * @return static
     */
    public function setGenSMSSign($genSMSSign)
    {
        $this->genSMSSign = $genSMSSign;
        return $this;
    }

    /**
     * Gets as cashierInfo
     *
     * @return \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType
     */
    public function getCashierInfo()
    {
        return $this->cashierInfo;
    }

    /**
     * Sets a new cashierInfo
     *
     * @param \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType $cashierInfo
     * @return static
     */
    public function setCashierInfo(\common\models\sbbolxml\request\AdmCashierType\CashierInfoAType $cashierInfo)
    {
        $this->cashierInfo = $cashierInfo;
        return $this;
    }

    /**
     * Gets as empowerment
     *
     * Информация о полномочиях
     *  Группа полей доступна для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @return \common\models\sbbolxml\request\AdmCashierType\EmpowermentAType
     */
    public function getEmpowerment()
    {
        return $this->empowerment;
    }

    /**
     * Sets a new empowerment
     *
     * Информация о полномочиях
     *  Группа полей доступна для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @param \common\models\sbbolxml\request\AdmCashierType\EmpowermentAType $empowerment
     * @return static
     */
    public function setEmpowerment(\common\models\sbbolxml\request\AdmCashierType\EmpowermentAType $empowerment)
    {
        $this->empowerment = $empowerment;
        return $this;
    }

    /**
     * Gets as deviceInfo
     *
     * Информация об устройстве внесения средств
     *
     * @return \common\models\sbbolxml\request\AdmCashierType\DeviceInfoAType
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
     * @param \common\models\sbbolxml\request\AdmCashierType\DeviceInfoAType $deviceInfo
     * @return static
     */
    public function setDeviceInfo(\common\models\sbbolxml\request\AdmCashierType\DeviceInfoAType $deviceInfo)
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
     * @param \common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType\TemplateAType $template
     */
    public function addToADMTemplates(\common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType\TemplateAType $template)
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
     * @return \common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType\TemplateAType[]
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
     * @param \common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType\TemplateAType[] $aDMTemplates
     * @return static
     */
    public function setADMTemplates(array $aDMTemplates)
    {
        $this->aDMTemplates = $aDMTemplates;
        return $this;
    }


}

