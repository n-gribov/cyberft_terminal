<?php

namespace common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType;

/**
 * Class representing StatementAType
 */
class StatementAType
{

    /**
     * Дата начала периода
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Тип выписки:
     *  1 - Выписка из лицевого счета
     *  2 - Выписка из лицевого счета с арестами и ограничениями
     *  3 - Выписка из лицевого счета (расширенная)
     *  4 - Выписка с приложениями
     *  5 - Выписка из лицевого счета (расширенная) с приложениями
     *
     * @property string $type
     */
    private $type = null;

    /**
     * @property string $account
     */
    private $account = null;

    /**
     * @property string $bic
     */
    private $bic = null;

    /**
     * Gets as beginDate
     *
     * Дата начала периода
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала периода
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания периода
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата окончания периода
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип выписки:
     *  1 - Выписка из лицевого счета
     *  2 - Выписка из лицевого счета с арестами и ограничениями
     *  3 - Выписка из лицевого счета (расширенная)
     *  4 - Выписка с приложениями
     *  5 - Выписка из лицевого счета (расширенная) с приложениями
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип выписки:
     *  1 - Выписка из лицевого счета
     *  2 - Выписка из лицевого счета с арестами и ограничениями
     *  3 - Выписка из лицевого счета (расширенная)
     *  4 - Выписка с приложениями
     *  5 - Выписка из лицевого счета (расширенная) с приложениями
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }


}

