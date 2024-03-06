<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayDocRuClientType
 *
 *
 * XSD Type: PayDocRuClient
 */
class PayDocRuClientType extends AbstractClientType
{

    /**
     * ИНН (от 5 до 12 или 0)
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
     * ИНН (от 5 до 12 или 0)
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
     * ИНН (от 5 до 12 или 0)
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

