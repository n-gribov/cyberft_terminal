<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing SignsAType
 */
class SignsAType
{

    /**
     * ЭП клиента
     *
     * @property \common\models\raiffeisenxml\request\DigitalSignType[] $sign
     */
    private $sign = [
        
    ];

    /**
     * Adds as sign
     *
     * ЭП клиента
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DigitalSignType $sign
     */
    public function addToSign(\common\models\raiffeisenxml\request\DigitalSignType $sign)
    {
        $this->sign[] = $sign;
        return $this;
    }

    /**
     * isset sign
     *
     * ЭП клиента
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSign($index)
    {
        return isset($this->sign[$index]);
    }

    /**
     * unset sign
     *
     * ЭП клиента
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSign($index)
    {
        unset($this->sign[$index]);
    }

    /**
     * Gets as sign
     *
     * ЭП клиента
     *
     * @return \common\models\raiffeisenxml\request\DigitalSignType[]
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * ЭП клиента
     *
     * @param \common\models\raiffeisenxml\request\DigitalSignType[] $sign
     * @return static
     */
    public function setSign(array $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

