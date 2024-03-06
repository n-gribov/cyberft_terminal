<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtBusinessAccNumBicType
 *
 * Реквизиты бизнес-счёта клиента (Реквизиты счета и БИК)
 * XSD Type: CorpCardExtBusinessAccNumBicType
 */
class CorpCardExtBusinessAccNumBicType
{

    /**
     * Номер бизнес-счёта
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * БИК банка бизнес-счёта
     *
     * @property string $accBic
     */
    private $accBic = null;

    /**
     * Код валюты бизнес-счёта
     *
     * @property string $accCurr
     */
    private $accCurr = null;

    /**
     * Gets as accNum
     *
     * Номер бизнес-счёта
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер бизнес-счёта
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as accBic
     *
     * БИК банка бизнес-счёта
     *
     * @return string
     */
    public function getAccBic()
    {
        return $this->accBic;
    }

    /**
     * Sets a new accBic
     *
     * БИК банка бизнес-счёта
     *
     * @param string $accBic
     * @return static
     */
    public function setAccBic($accBic)
    {
        $this->accBic = $accBic;
        return $this;
    }

    /**
     * Gets as accCurr
     *
     * Код валюты бизнес-счёта
     *
     * @return string
     */
    public function getAccCurr()
    {
        return $this->accCurr;
    }

    /**
     * Sets a new accCurr
     *
     * Код валюты бизнес-счёта
     *
     * @param string $accCurr
     * @return static
     */
    public function setAccCurr($accCurr)
    {
        $this->accCurr = $accCurr;
        return $this;
    }


}

