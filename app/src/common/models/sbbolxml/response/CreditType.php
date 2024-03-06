<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CreditType
 *
 *
 * XSD Type: Credit
 */
class CreditType
{

    /**
     * Признак «Целевое поручение» : 0 - не установлен, 1 - установлен
     *
     * @property boolean $flagTargetAssignment
     */
    private $flagTargetAssignment = null;

    /**
     * Признак «Использовать собственные средства»: 0 - не установлен, 1 - установлен
     *
     * @property boolean $flagUseOwnMeans
     */
    private $flagUseOwnMeans = null;

    /**
     * Номер кредитного договора (в базе varchar(255), на ввод ограничили до пока 30)
     *
     * @property string $credConNum
     */
    private $credConNum = null;

    /**
     * Gets as flagTargetAssignment
     *
     * Признак «Целевое поручение» : 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getFlagTargetAssignment()
    {
        return $this->flagTargetAssignment;
    }

    /**
     * Sets a new flagTargetAssignment
     *
     * Признак «Целевое поручение» : 0 - не установлен, 1 - установлен
     *
     * @param boolean $flagTargetAssignment
     * @return static
     */
    public function setFlagTargetAssignment($flagTargetAssignment)
    {
        $this->flagTargetAssignment = $flagTargetAssignment;
        return $this;
    }

    /**
     * Gets as flagUseOwnMeans
     *
     * Признак «Использовать собственные средства»: 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getFlagUseOwnMeans()
    {
        return $this->flagUseOwnMeans;
    }

    /**
     * Sets a new flagUseOwnMeans
     *
     * Признак «Использовать собственные средства»: 0 - не установлен, 1 - установлен
     *
     * @param boolean $flagUseOwnMeans
     * @return static
     */
    public function setFlagUseOwnMeans($flagUseOwnMeans)
    {
        $this->flagUseOwnMeans = $flagUseOwnMeans;
        return $this;
    }

    /**
     * Gets as credConNum
     *
     * Номер кредитного договора (в базе varchar(255), на ввод ограничили до пока 30)
     *
     * @return string
     */
    public function getCredConNum()
    {
        return $this->credConNum;
    }

    /**
     * Sets a new credConNum
     *
     * Номер кредитного договора (в базе varchar(255), на ввод ограничили до пока 30)
     *
     * @param string $credConNum
     * @return static
     */
    public function setCredConNum($credConNum)
    {
        $this->credConNum = $credConNum;
        return $this;
    }


}

