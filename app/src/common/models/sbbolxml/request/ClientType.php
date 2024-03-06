<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ClientType
 *
 *
 * XSD Type: Client
 */
class ClientType extends AbstractClientType
{

    /**
     * ИНН (до 12)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП (до 9)
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Gets as inn
     *
     * ИНН (до 12)
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН (до 12)
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as kpp
     *
     * КПП (до 9)
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП (до 9)
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }


}

