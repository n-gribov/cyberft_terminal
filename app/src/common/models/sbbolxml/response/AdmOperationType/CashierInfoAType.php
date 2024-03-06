<?php

namespace common\models\sbbolxml\response\AdmOperationType;

/**
 * Class representing CashierInfoAType
 */
class CashierInfoAType
{

    /**
     * ФИО вносителя средств
     *
     * @property string $cashierFio
     */
    private $cashierFio = null;

    /**
     * БИК счета
     *
     * @property string $cashierId
     */
    private $cashierId = null;

    /**
     * Наименование шаблона внесения средств
     *
     * @property string $templateName
     */
    private $templateName = null;

    /**
     * Gets as cashierFio
     *
     * ФИО вносителя средств
     *
     * @return string
     */
    public function getCashierFio()
    {
        return $this->cashierFio;
    }

    /**
     * Sets a new cashierFio
     *
     * ФИО вносителя средств
     *
     * @param string $cashierFio
     * @return static
     */
    public function setCashierFio($cashierFio)
    {
        $this->cashierFio = $cashierFio;
        return $this;
    }

    /**
     * Gets as cashierId
     *
     * БИК счета
     *
     * @return string
     */
    public function getCashierId()
    {
        return $this->cashierId;
    }

    /**
     * Sets a new cashierId
     *
     * БИК счета
     *
     * @param string $cashierId
     * @return static
     */
    public function setCashierId($cashierId)
    {
        $this->cashierId = $cashierId;
        return $this;
    }

    /**
     * Gets as templateName
     *
     * Наименование шаблона внесения средств
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Sets a new templateName
     *
     * Наименование шаблона внесения средств
     *
     * @param string $templateName
     * @return static
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }


}

