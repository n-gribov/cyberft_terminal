<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing VBKInfoType
 *
 * Информация из ведомости банковского контроля
 * XSD Type: VBKInfo
 */
class VBKInfoType
{

    /**
     * Номер строки
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Номер ПС
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Информация о контраке/ кредитном договоре
     *
     * @property \common\models\sbbolxml\response\VBKInfoContractType $contract
     */
    private $contract = null;

    /**
     * XML-сообщение, содержащее ВБК в соответствии с форматом, предписанным Инстуркции 138-И
     *
     * @property string $xMLMessage
     */
    private $xMLMessage = null;

    /**
     * Gets as lineNumber
     *
     * Номер строки
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * Номер строки
     *
     * @param integer $lineNumber
     * @return static
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Gets as dealPassNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Номер ПС
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as contract
     *
     * Информация о контраке/ кредитном договоре
     *
     * @return \common\models\sbbolxml\response\VBKInfoContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контраке/ кредитном договоре
     *
     * @param \common\models\sbbolxml\response\VBKInfoContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\response\VBKInfoContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Gets as xMLMessage
     *
     * XML-сообщение, содержащее ВБК в соответствии с форматом, предписанным Инстуркции 138-И
     *
     * @return string
     */
    public function getXMLMessage()
    {
        return $this->xMLMessage;
    }

    /**
     * Sets a new xMLMessage
     *
     * XML-сообщение, содержащее ВБК в соответствии с форматом, предписанным Инстуркции 138-И
     *
     * @param string $xMLMessage
     * @return static
     */
    public function setXMLMessage($xMLMessage)
    {
        $this->xMLMessage = $xMLMessage;
        return $this;
    }


}

