<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealAnsType
 *
 * Ответ о подтверждении сделки
 * XSD Type: DealAns
 */
class DealAnsType
{

    /**
     * Идентификатор сообщения о подтверждении сделки АС СББОЛ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Решение о подтверждении сделки
     *
     * @property boolean $decision
     */
    private $decision = null;

    /**
     * Идентификатор платежной инструкции для сделки или для первой ноги
     *
     * @property string $siId
     */
    private $siId = null;

    /**
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @property string $nsiText
     */
    private $nsiText = null;

    /**
     * Идентификатор платежной инструкции для второй ноги
     *
     * @property string $siId2
     */
    private $siId2 = null;

    /**
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @property string $nsiText2
     */
    private $nsiText2 = null;

    /**
     * Подпись документа "Сообщение о подтверждении сделки"
     *
     * @property \common\models\sbbolxml\request\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор сообщения о подтверждении сделки АС СББОЛ
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
     * Идентификатор сообщения о подтверждении сделки АС СББОЛ
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
     * Gets as decision
     *
     * Решение о подтверждении сделки
     *
     * @return boolean
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Sets a new decision
     *
     * Решение о подтверждении сделки
     *
     * @param boolean $decision
     * @return static
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;
        return $this;
    }

    /**
     * Gets as siId
     *
     * Идентификатор платежной инструкции для сделки или для первой ноги
     *
     * @return string
     */
    public function getSiId()
    {
        return $this->siId;
    }

    /**
     * Sets a new siId
     *
     * Идентификатор платежной инструкции для сделки или для первой ноги
     *
     * @param string $siId
     * @return static
     */
    public function setSiId($siId)
    {
        $this->siId = $siId;
        return $this;
    }

    /**
     * Gets as nsiText
     *
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @return string
     */
    public function getNsiText()
    {
        return $this->nsiText;
    }

    /**
     * Sets a new nsiText
     *
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @param string $nsiText
     * @return static
     */
    public function setNsiText($nsiText)
    {
        $this->nsiText = $nsiText;
        return $this;
    }

    /**
     * Gets as siId2
     *
     * Идентификатор платежной инструкции для второй ноги
     *
     * @return string
     */
    public function getSiId2()
    {
        return $this->siId2;
    }

    /**
     * Sets a new siId2
     *
     * Идентификатор платежной инструкции для второй ноги
     *
     * @param string $siId2
     * @return static
     */
    public function setSiId2($siId2)
    {
        $this->siId2 = $siId2;
        return $this;
    }

    /**
     * Gets as nsiText2
     *
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @return string
     */
    public function getNsiText2()
    {
        return $this->nsiText2;
    }

    /**
     * Sets a new nsiText2
     *
     * Комментарий к новой платежной инструкции для сделки или для первой ноги
     *
     * @param string $nsiText2
     * @return static
     */
    public function setNsiText2($nsiText2)
    {
        $this->nsiText2 = $nsiText2;
        return $this;
    }

    /**
     * Gets as sign
     *
     * Подпись документа "Сообщение о подтверждении сделки"
     *
     * @return \common\models\sbbolxml\request\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * Подпись документа "Сообщение о подтверждении сделки"
     *
     * @param \common\models\sbbolxml\request\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\request\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

