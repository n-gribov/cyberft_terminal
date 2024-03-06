<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocBaseType
 *
 *
 * XSD Type: DocBase
 */
class DocBaseType
{

    /**
     * Идентификатор документа
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * 0 – обычная передача на выполнение
     *  1 – передано для подписания в ИК
     *
     * @property boolean $sentForSign
     */
    private $sentForSign = null;

    /**
     * @property \common\models\sbbolxml\request\ClientStateType $clientState
     */
    private $clientState = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа
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
     * Идентификатор документа
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
     * Gets as sentForSign
     *
     * 0 – обычная передача на выполнение
     *  1 – передано для подписания в ИК
     *
     * @return boolean
     */
    public function getSentForSign()
    {
        return $this->sentForSign;
    }

    /**
     * Sets a new sentForSign
     *
     * 0 – обычная передача на выполнение
     *  1 – передано для подписания в ИК
     *
     * @param boolean $sentForSign
     * @return static
     */
    public function setSentForSign($sentForSign)
    {
        $this->sentForSign = $sentForSign;
        return $this;
    }

    /**
     * Gets as clientState
     *
     * @return \common\models\sbbolxml\request\ClientStateType
     */
    public function getClientState()
    {
        return $this->clientState;
    }

    /**
     * Sets a new clientState
     *
     * @param \common\models\sbbolxml\request\ClientStateType $clientState
     * @return static
     */
    public function setClientState(\common\models\sbbolxml\request\ClientStateType $clientState)
    {
        $this->clientState = $clientState;
        return $this;
    }


}

