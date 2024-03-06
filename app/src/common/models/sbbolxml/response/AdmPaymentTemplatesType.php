<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmPaymentTemplatesType
 *
 * Шаблоны внесения средств
 * XSD Type: AdmPaymentTemplates
 */
class AdmPaymentTemplatesType
{

    /**
     * Идентификатор последнего обновления справочника
     *
     * @property string $stepId
     */
    private $stepId = null;

    /**
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType[] $admPayTemplate
     */
    private $admPayTemplate = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @return string
     */
    public function getStepId()
    {
        return $this->stepId;
    }

    /**
     * Sets a new stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @param string $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }

    /**
     * Adds as admPayTemplate
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType $admPayTemplate
     */
    public function addToAdmPayTemplate(\common\models\sbbolxml\response\AdmPaymentTemplateType $admPayTemplate)
    {
        $this->admPayTemplate[] = $admPayTemplate;
        return $this;
    }

    /**
     * isset admPayTemplate
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAdmPayTemplate($index)
    {
        return isset($this->admPayTemplate[$index]);
    }

    /**
     * unset admPayTemplate
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAdmPayTemplate($index)
    {
        unset($this->admPayTemplate[$index]);
    }

    /**
     * Gets as admPayTemplate
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType[]
     */
    public function getAdmPayTemplate()
    {
        return $this->admPayTemplate;
    }

    /**
     * Sets a new admPayTemplate
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType[] $admPayTemplate
     * @return static
     */
    public function setAdmPayTemplate(array $admPayTemplate)
    {
        $this->admPayTemplate = $admPayTemplate;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

