<?php

namespace common\models\sbbolxml\response\StatementType;

/**
 * Class representing OverAccInfoAType
 */
class OverAccInfoAType
{

    /**
     * Общий лимит овердрафта в валюте счёта
     *
     * @property float $limit
     */
    private $limit = null;

    /**
     * Неизрасходованный лимит овердрафта в валюте счёта.
     *  Информация для разработчиков СББОЛ: передавать в этом поле SUMOVD, кот. приходит из
     *  Гаммы
     *
     * @property float $restLimit
     */
    private $restLimit = null;

    /**
     * Использованный лимит
     *
     * @property float $usedLimit
     */
    private $usedLimit = null;

    /**
     * Состояние счета (Открыт, закрыт, ограничения)
     *
     * @property string $accountState
     */
    private $accountState = null;

    /**
     * Gets as limit
     *
     * Общий лимит овердрафта в валюте счёта
     *
     * @return float
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets a new limit
     *
     * Общий лимит овердрафта в валюте счёта
     *
     * @param float $limit
     * @return static
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Gets as restLimit
     *
     * Неизрасходованный лимит овердрафта в валюте счёта.
     *  Информация для разработчиков СББОЛ: передавать в этом поле SUMOVD, кот. приходит из
     *  Гаммы
     *
     * @return float
     */
    public function getRestLimit()
    {
        return $this->restLimit;
    }

    /**
     * Sets a new restLimit
     *
     * Неизрасходованный лимит овердрафта в валюте счёта.
     *  Информация для разработчиков СББОЛ: передавать в этом поле SUMOVD, кот. приходит из
     *  Гаммы
     *
     * @param float $restLimit
     * @return static
     */
    public function setRestLimit($restLimit)
    {
        $this->restLimit = $restLimit;
        return $this;
    }

    /**
     * Gets as usedLimit
     *
     * Использованный лимит
     *
     * @return float
     */
    public function getUsedLimit()
    {
        return $this->usedLimit;
    }

    /**
     * Sets a new usedLimit
     *
     * Использованный лимит
     *
     * @param float $usedLimit
     * @return static
     */
    public function setUsedLimit($usedLimit)
    {
        $this->usedLimit = $usedLimit;
        return $this;
    }

    /**
     * Gets as accountState
     *
     * Состояние счета (Открыт, закрыт, ограничения)
     *
     * @return string
     */
    public function getAccountState()
    {
        return $this->accountState;
    }

    /**
     * Sets a new accountState
     *
     * Состояние счета (Открыт, закрыт, ограничения)
     *
     * @param string $accountState
     * @return static
     */
    public function setAccountState($accountState)
    {
        $this->accountState = $accountState;
        return $this;
    }


}

