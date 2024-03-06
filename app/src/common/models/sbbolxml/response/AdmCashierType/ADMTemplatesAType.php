<?php

namespace common\models\sbbolxml\response\AdmCashierType;

/**
 * Class representing ADMTemplatesAType
 */
class ADMTemplatesAType
{

    /**
     * Шаблоны внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[] $template
     */
    private $template = array(
        
    );

    /**
     * Adds as template
     *
     * Шаблоны внесения средств
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType $template
     */
    public function addToTemplate(\common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType $template)
    {
        $this->template[] = $template;
        return $this;
    }

    /**
     * isset template
     *
     * Шаблоны внесения средств
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTemplate($index)
    {
        return isset($this->template[$index]);
    }

    /**
     * unset template
     *
     * Шаблоны внесения средств
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTemplate($index)
    {
        unset($this->template[$index]);
    }

    /**
     * Gets as template
     *
     * Шаблоны внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[]
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets a new template
     *
     * Шаблоны внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\ADMTemplatesAType\TemplateAType[] $template
     * @return static
     */
    public function setTemplate(array $template)
    {
        $this->template = $template;
        return $this;
    }


}

