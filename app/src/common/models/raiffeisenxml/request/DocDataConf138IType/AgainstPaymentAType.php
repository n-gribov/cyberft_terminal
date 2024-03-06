<?php

namespace common\models\raiffeisenxml\request\DocDataConf138IType;

/**
 * Class representing AgainstPaymentAType
 */
class AgainstPaymentAType
{

    /**
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Gets as docNum
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }


}

