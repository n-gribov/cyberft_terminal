<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing GenericLetterToBankTicketType
 *
 * Дополнительная информация по ПСФ
 * XSD Type: GenericLetterToBankTicket
 */
class GenericLetterToBankTicketType
{

    /**
     * Номер ПСФ в CRM
     *
     * @property string $crmNum
     */
    private $crmNum = null;

    /**
     * Идентификатор обращения в CRM
     *
     * @property string $crmId
     */
    private $crmId = null;

    /**
     * Gets as crmNum
     *
     * Номер ПСФ в CRM
     *
     * @return string
     */
    public function getCrmNum()
    {
        return $this->crmNum;
    }

    /**
     * Sets a new crmNum
     *
     * Номер ПСФ в CRM
     *
     * @param string $crmNum
     * @return static
     */
    public function setCrmNum($crmNum)
    {
        $this->crmNum = $crmNum;
        return $this;
    }

    /**
     * Gets as crmId
     *
     * Идентификатор обращения в CRM
     *
     * @return string
     */
    public function getCrmId()
    {
        return $this->crmId;
    }

    /**
     * Sets a new crmId
     *
     * Идентификатор обращения в CRM
     *
     * @param string $crmId
     * @return static
     */
    public function setCrmId($crmId)
    {
        $this->crmId = $crmId;
        return $this;
    }


}

