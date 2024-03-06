<?php

namespace common\models\sbbolxml\request\AdmCashierType\ADMTemplatesAType;

/**
 * Class representing TemplateAType
 */
class TemplateAType
{

    /**
     * Идентификатор шаблона внесения средств
     *
     * @property string $templateId
     */
    private $templateId = null;

    /**
     * Gets as templateId
     *
     * Идентификатор шаблона внесения средств
     *
     * @return string
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * Sets a new templateId
     *
     * Идентификатор шаблона внесения средств
     *
     * @param string $templateId
     * @return static
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }


}

