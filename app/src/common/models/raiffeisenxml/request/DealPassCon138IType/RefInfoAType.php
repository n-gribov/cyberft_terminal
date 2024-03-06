<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IType;

/**
 * Class representing RefInfoAType
 */
class RefInfoAType
{

    /**
     * 7.1 Способ предоставления резидентом документов для оформления. Передавать «1», если значение поля в документе «1 – на бумажном носителе», передавать «2», если значение поля в документе «2 – в электронном виде»
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
     * Gets as resInType
     *
     * 7.1 Способ предоставления резидентом документов для оформления. Передавать «1», если значение поля в документе «1 – на бумажном носителе», передавать «2», если значение поля в документе «2 – в электронном виде»
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
     * 7.1 Способ предоставления резидентом документов для оформления. Передавать «1», если значение поля в документе «1 – на бумажном носителе», передавать «2», если значение поля в документе «2 – в электронном виде»
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


}

