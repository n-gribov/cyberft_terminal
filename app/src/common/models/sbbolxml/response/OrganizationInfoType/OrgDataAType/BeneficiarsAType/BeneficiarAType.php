<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType;

/**
 * Class representing BeneficiarAType
 */
class BeneficiarAType
{

    /**
     * Идентификатор документа в СББ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @property \common\models\sbbolxml\response\Beneficiar59Type $beneficiar59
     */
    private $beneficiar59 = null;

    /**
     * 56: Банк-посредник
     *
     * @property \common\models\sbbolxml\response\ImediaBank56Type $imediaBank56
     */
    private $imediaBank56 = null;

    /**
     * Банк бенефициара
     *
     * @property \common\models\sbbolxml\response\BankBeneficiar57Type $bankBeneficiar57
     */
    private $bankBeneficiar57 = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType\OptionsAType $options
     */
    private $options = null;

    /**
     * Платежное поручение в иностранной валюте
     *  по умолчанию чек выстален
     *
     * @property boolean $payDocCurUse
     */
    private $payDocCurUse = null;

    /**
     * Прочие документы
     *
     * @property boolean $otherDoc
     */
    private $otherDoc = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Назначение платежа
     *
     * @property string $paymentDetails70
     */
    private $paymentDetails70 = null;

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Направление платежа (Платеж внутри или вне
     *  СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @property boolean $paymentDirection
     */
    private $paymentDirection = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в СББ
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
     * Идентификатор документа в СББ
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
     * Gets as beneficiar59
     *
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @return \common\models\sbbolxml\response\Beneficiar59Type
     */
    public function getBeneficiar59()
    {
        return $this->beneficiar59;
    }

    /**
     * Sets a new beneficiar59
     *
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @param \common\models\sbbolxml\response\Beneficiar59Type $beneficiar59
     * @return static
     */
    public function setBeneficiar59(\common\models\sbbolxml\response\Beneficiar59Type $beneficiar59)
    {
        $this->beneficiar59 = $beneficiar59;
        return $this;
    }

    /**
     * Gets as imediaBank56
     *
     * 56: Банк-посредник
     *
     * @return \common\models\sbbolxml\response\ImediaBank56Type
     */
    public function getImediaBank56()
    {
        return $this->imediaBank56;
    }

    /**
     * Sets a new imediaBank56
     *
     * 56: Банк-посредник
     *
     * @param \common\models\sbbolxml\response\ImediaBank56Type $imediaBank56
     * @return static
     */
    public function setImediaBank56(\common\models\sbbolxml\response\ImediaBank56Type $imediaBank56)
    {
        $this->imediaBank56 = $imediaBank56;
        return $this;
    }

    /**
     * Gets as bankBeneficiar57
     *
     * Банк бенефициара
     *
     * @return \common\models\sbbolxml\response\BankBeneficiar57Type
     */
    public function getBankBeneficiar57()
    {
        return $this->bankBeneficiar57;
    }

    /**
     * Sets a new bankBeneficiar57
     *
     * Банк бенефициара
     *
     * @param \common\models\sbbolxml\response\BankBeneficiar57Type $bankBeneficiar57
     * @return static
     */
    public function setBankBeneficiar57(\common\models\sbbolxml\response\BankBeneficiar57Type $bankBeneficiar57)
    {
        $this->bankBeneficiar57 = $bankBeneficiar57;
        return $this;
    }

    /**
     * Gets as options
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType\OptionsAType
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets a new options
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType\OptionsAType $options
     * @return static
     */
    public function setOptions(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType\OptionsAType $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Gets as payDocCurUse
     *
     * Платежное поручение в иностранной валюте
     *  по умолчанию чек выстален
     *
     * @return boolean
     */
    public function getPayDocCurUse()
    {
        return $this->payDocCurUse;
    }

    /**
     * Sets a new payDocCurUse
     *
     * Платежное поручение в иностранной валюте
     *  по умолчанию чек выстален
     *
     * @param boolean $payDocCurUse
     * @return static
     */
    public function setPayDocCurUse($payDocCurUse)
    {
        $this->payDocCurUse = $payDocCurUse;
        return $this;
    }

    /**
     * Gets as otherDoc
     *
     * Прочие документы
     *
     * @return boolean
     */
    public function getOtherDoc()
    {
        return $this->otherDoc;
    }

    /**
     * Sets a new otherDoc
     *
     * Прочие документы
     *
     * @param boolean $otherDoc
     * @return static
     */
    public function setOtherDoc($otherDoc)
    {
        $this->otherDoc = $otherDoc;
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

    /**
     * Gets as paymentDetails70
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPaymentDetails70()
    {
        return $this->paymentDetails70;
    }

    /**
     * Sets a new paymentDetails70
     *
     * Назначение платежа
     *
     * @param string $paymentDetails70
     * @return static
     */
    public function setPaymentDetails70($paymentDetails70)
    {
        $this->paymentDetails70 = $paymentDetails70;
        return $this;
    }

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as paymentDirection
     *
     * Направление платежа (Платеж внутри или вне
     *  СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @return boolean
     */
    public function getPaymentDirection()
    {
        return $this->paymentDirection;
    }

    /**
     * Sets a new paymentDirection
     *
     * Направление платежа (Платеж внутри или вне
     *  СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @param boolean $paymentDirection
     * @return static
     */
    public function setPaymentDirection($paymentDirection)
    {
        $this->paymentDirection = $paymentDirection;
        return $this;
    }


}

