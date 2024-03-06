<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IntCtrlStatementXML181IType
 *
 *
 * XSD Type: IntCtrlStatementXML181I
 */
class IntCtrlStatementXML181IType
{

    /**
     * Глобальный идентификатор документа ВБК в АС еКС
     *
     * @property string $docExtGuid
     */
    private $docExtGuid = null;

    /**
     * Идентификатор документа в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Xml-файл ВБК в формате Инструкции
     *
     * @property string $xmlBody
     */
    private $xmlBody = null;

    /**
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @property \common\models\sbbolxml\response\FinalTransactDataType[] $finalTransact
     */
    private $finalTransact = null;

    /**
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @property \common\models\sbbolxml\response\FinalDebtPrincipalDataType[] $finalDebtPrincipal
     */
    private $finalDebtPrincipal = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtGuid
     *
     * Глобальный идентификатор документа ВБК в АС еКС
     *
     * @return string
     */
    public function getDocExtGuid()
    {
        return $this->docExtGuid;
    }

    /**
     * Sets a new docExtGuid
     *
     * Глобальный идентификатор документа ВБК в АС еКС
     *
     * @param string $docExtGuid
     * @return static
     */
    public function setDocExtGuid($docExtGuid)
    {
        $this->docExtGuid = $docExtGuid;
        return $this;
    }

    /**
     * Gets as docId
     *
     * Идентификатор документа в СББОЛ
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
     * Идентификатор документа в СББОЛ
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
     * Gets as xmlBody
     *
     * Xml-файл ВБК в формате Инструкции
     *
     * @return string
     */
    public function getXmlBody()
    {
        return $this->xmlBody;
    }

    /**
     * Sets a new xmlBody
     *
     * Xml-файл ВБК в формате Инструкции
     *
     * @param string $xmlBody
     * @return static
     */
    public function setXmlBody($xmlBody)
    {
        $this->xmlBody = $xmlBody;
        return $this;
    }

    /**
     * Adds as finalTransactData
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @return static
     * @param \common\models\sbbolxml\response\FinalTransactDataType $finalTransactData
     */
    public function addToFinalTransact(\common\models\sbbolxml\response\FinalTransactDataType $finalTransactData)
    {
        $this->finalTransact[] = $finalTransactData;
        return $this;
    }

    /**
     * isset finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFinalTransact($index)
    {
        return isset($this->finalTransact[$index]);
    }

    /**
     * unset finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFinalTransact($index)
    {
        unset($this->finalTransact[$index]);
    }

    /**
     * Gets as finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @return \common\models\sbbolxml\response\FinalTransactDataType[]
     */
    public function getFinalTransact()
    {
        return $this->finalTransact;
    }

    /**
     * Sets a new finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param \common\models\sbbolxml\response\FinalTransactDataType[] $finalTransact
     * @return static
     */
    public function setFinalTransact(array $finalTransact)
    {
        $this->finalTransact = $finalTransact;
        return $this;
    }

    /**
     * Adds as finalDebtPrincipalData
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @return static
     * @param \common\models\sbbolxml\response\FinalDebtPrincipalDataType $finalDebtPrincipalData
     */
    public function addToFinalDebtPrincipal(\common\models\sbbolxml\response\FinalDebtPrincipalDataType $finalDebtPrincipalData)
    {
        $this->finalDebtPrincipal[] = $finalDebtPrincipalData;
        return $this;
    }

    /**
     * isset finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFinalDebtPrincipal($index)
    {
        return isset($this->finalDebtPrincipal[$index]);
    }

    /**
     * unset finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFinalDebtPrincipal($index)
    {
        unset($this->finalDebtPrincipal[$index]);
    }

    /**
     * Gets as finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @return \common\models\sbbolxml\response\FinalDebtPrincipalDataType[]
     */
    public function getFinalDebtPrincipal()
    {
        return $this->finalDebtPrincipal;
    }

    /**
     * Sets a new finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param \common\models\sbbolxml\response\FinalDebtPrincipalDataType[] $finalDebtPrincipal
     * @return static
     */
    public function setFinalDebtPrincipal(array $finalDebtPrincipal)
    {
        $this->finalDebtPrincipal = $finalDebtPrincipal;
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

