<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmOperationsType
 *
 * Операции внесения средств
 * XSD Type: AdmOperations
 */
class AdmOperationsType
{

    /**
     * Идентификатор последнего обновления справочника
     *
     * @property string $stepId
     */
    private $stepId = null;

    /**
     * Операция внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmOperationType[] $admOperation
     */
    private $admOperation = array(
        
    );

    /**
     * Техн ЭП СББОЛ
     *
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
     * Adds as admOperation
     *
     * Операция внесения средств
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmOperationType $admOperation
     */
    public function addToAdmOperation(\common\models\sbbolxml\response\AdmOperationType $admOperation)
    {
        $this->admOperation[] = $admOperation;
        return $this;
    }

    /**
     * isset admOperation
     *
     * Операция внесения средств
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAdmOperation($index)
    {
        return isset($this->admOperation[$index]);
    }

    /**
     * unset admOperation
     *
     * Операция внесения средств
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAdmOperation($index)
    {
        unset($this->admOperation[$index]);
    }

    /**
     * Gets as admOperation
     *
     * Операция внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmOperationType[]
     */
    public function getAdmOperation()
    {
        return $this->admOperation;
    }

    /**
     * Sets a new admOperation
     *
     * Операция внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmOperationType[] $admOperation
     * @return static
     */
    public function setAdmOperation(array $admOperation)
    {
        $this->admOperation = $admOperation;
        return $this;
    }

    /**
     * Gets as sign
     *
     * Техн ЭП СББОЛ
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
     * Техн ЭП СББОЛ
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

