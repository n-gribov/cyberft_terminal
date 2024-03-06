<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RzkParamsType
 *
 * Параметры обработки документа в РЦК (СБК)
 * XSD Type: RzkParams
 */
class RzkParamsType
{

    /**
     * Идентификатор структуры (поле РЦК)
     *
     * @property integer $budgetId
     */
    private $budgetId = null;

    /**
     * Структура (поле РЦК)
     *
     * @property string $budgetСaption
     */
    private $budgetСaption = null;

    /**
     * Идентификатор Центра ответственности (поле РЦК)
     *
     * @property integer $estimateId
     */
    private $estimateId = null;

    /**
     * Центр ответственности (поле РЦК)
     *
     * @property string $estimateCaption
     */
    private $estimateCaption = null;

    /**
     * PRODUCT (поле РЦК). Если документ передается в рамках прямого управления счетом,
     *  то указывается значение "DirectControl"
     *
     * @property string $product
     */
    private $product = null;

    /**
     * Доп. статус СБК (поле РЦК)
     *
     * @property string $rzkStatus
     */
    private $rzkStatus = null;

    /**
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @property string $rzkAction
     */
    private $rzkAction = null;

    /**
     * Признак «Документ по распределенной схеме»: 0 - не включен 1 –включен. По
     *  умолчанию установлен признак 0 - не включен.
     *
     * @property boolean $distributedSign
     */
    private $distributedSign = null;

    /**
     * Gets as budgetId
     *
     * Идентификатор структуры (поле РЦК)
     *
     * @return integer
     */
    public function getBudgetId()
    {
        return $this->budgetId;
    }

    /**
     * Sets a new budgetId
     *
     * Идентификатор структуры (поле РЦК)
     *
     * @param integer $budgetId
     * @return static
     */
    public function setBudgetId($budgetId)
    {
        $this->budgetId = $budgetId;
        return $this;
    }

    /**
     * Gets as budgetСaption
     *
     * Структура (поле РЦК)
     *
     * @return string
     */
    public function getBudgetСaption()
    {
        return $this->budgetСaption;
    }

    /**
     * Sets a new budgetСaption
     *
     * Структура (поле РЦК)
     *
     * @param string $budgetСaption
     * @return static
     */
    public function setBudgetСaption($budgetСaption)
    {
        $this->budgetСaption = $budgetСaption;
        return $this;
    }

    /**
     * Gets as estimateId
     *
     * Идентификатор Центра ответственности (поле РЦК)
     *
     * @return integer
     */
    public function getEstimateId()
    {
        return $this->estimateId;
    }

    /**
     * Sets a new estimateId
     *
     * Идентификатор Центра ответственности (поле РЦК)
     *
     * @param integer $estimateId
     * @return static
     */
    public function setEstimateId($estimateId)
    {
        $this->estimateId = $estimateId;
        return $this;
    }

    /**
     * Gets as estimateCaption
     *
     * Центр ответственности (поле РЦК)
     *
     * @return string
     */
    public function getEstimateCaption()
    {
        return $this->estimateCaption;
    }

    /**
     * Sets a new estimateCaption
     *
     * Центр ответственности (поле РЦК)
     *
     * @param string $estimateCaption
     * @return static
     */
    public function setEstimateCaption($estimateCaption)
    {
        $this->estimateCaption = $estimateCaption;
        return $this;
    }

    /**
     * Gets as product
     *
     * PRODUCT (поле РЦК). Если документ передается в рамках прямого управления счетом,
     *  то указывается значение "DirectControl"
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets a new product
     *
     * PRODUCT (поле РЦК). Если документ передается в рамках прямого управления счетом,
     *  то указывается значение "DirectControl"
     *
     * @param string $product
     * @return static
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Gets as rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkStatus()
    {
        return $this->rzkStatus;
    }

    /**
     * Sets a new rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @param string $rzkStatus
     * @return static
     */
    public function setRzkStatus($rzkStatus)
    {
        $this->rzkStatus = $rzkStatus;
        return $this;
    }

    /**
     * Gets as rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkAction()
    {
        return $this->rzkAction;
    }

    /**
     * Sets a new rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @param string $rzkAction
     * @return static
     */
    public function setRzkAction($rzkAction)
    {
        $this->rzkAction = $rzkAction;
        return $this;
    }

    /**
     * Gets as distributedSign
     *
     * Признак «Документ по распределенной схеме»: 0 - не включен 1 –включен. По
     *  умолчанию установлен признак 0 - не включен.
     *
     * @return boolean
     */
    public function getDistributedSign()
    {
        return $this->distributedSign;
    }

    /**
     * Sets a new distributedSign
     *
     * Признак «Документ по распределенной схеме»: 0 - не включен 1 –включен. По
     *  умолчанию установлен признак 0 - не включен.
     *
     * @param boolean $distributedSign
     * @return static
     */
    public function setDistributedSign($distributedSign)
    {
        $this->distributedSign = $distributedSign;
        return $this;
    }


}

