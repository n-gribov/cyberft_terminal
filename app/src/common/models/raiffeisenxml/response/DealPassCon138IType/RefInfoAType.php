<?php

namespace common\models\raiffeisenxml\response\DealPassCon138IType;

/**
 * Class representing RefInfoAType
 */
class RefInfoAType
{

    /**
     * 7.1 Способ предоставления резидентом документов для оформления
     *
     * @property string $resInType
     */
    private $resInType = null;

    /**
     * 7.1 Дата предоставления резидентом документов для оформления
     *
     * @property \DateTime $resInDate
     */
    private $resInDate = null;

    /**
     * 7.2 Способ направления резиденту оформленного ПС
     *
     * @property string $regSendType
     */
    private $regSendType = null;

    /**
     * 7.2 Дата направления резиденту оформленного ПС
     *
     * @property \DateTime $reqSendDate
     */
    private $reqSendDate = null;

    /**
     * Gets as resInType
     *
     * 7.1 Способ предоставления резидентом документов для оформления
     *
     * @return string
     */
    public function getResInType()
    {
        return $this->resInType;
    }

    /**
     * Sets a new resInType
     *
     * 7.1 Способ предоставления резидентом документов для оформления
     *
     * @param string $resInType
     * @return static
     */
    public function setResInType($resInType)
    {
        $this->resInType = $resInType;
        return $this;
    }

    /**
     * Gets as resInDate
     *
     * 7.1 Дата предоставления резидентом документов для оформления
     *
     * @return \DateTime
     */
    public function getResInDate()
    {
        return $this->resInDate;
    }

    /**
     * Sets a new resInDate
     *
     * 7.1 Дата предоставления резидентом документов для оформления
     *
     * @param \DateTime $resInDate
     * @return static
     */
    public function setResInDate(\DateTime $resInDate)
    {
        $this->resInDate = $resInDate;
        return $this;
    }

    /**
     * Gets as regSendType
     *
     * 7.2 Способ направления резиденту оформленного ПС
     *
     * @return string
     */
    public function getRegSendType()
    {
        return $this->regSendType;
    }

    /**
     * Sets a new regSendType
     *
     * 7.2 Способ направления резиденту оформленного ПС
     *
     * @param string $regSendType
     * @return static
     */
    public function setRegSendType($regSendType)
    {
        $this->regSendType = $regSendType;
        return $this;
    }

    /**
     * Gets as reqSendDate
     *
     * 7.2 Дата направления резиденту оформленного ПС
     *
     * @return \DateTime
     */
    public function getReqSendDate()
    {
        return $this->reqSendDate;
    }

    /**
     * Sets a new reqSendDate
     *
     * 7.2 Дата направления резиденту оформленного ПС
     *
     * @param \DateTime $reqSendDate
     * @return static
     */
    public function setReqSendDate(\DateTime $reqSendDate)
    {
        $this->reqSendDate = $reqSendDate;
        return $this;
    }


}

