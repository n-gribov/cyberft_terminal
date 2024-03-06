<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IntCtrlStatementHeaderType
 *
 * Заголовок ВБК
 * XSD Type: IntCtrlStatementHeader
 */
class IntCtrlStatementHeaderType
{

    /**
     * Основные данные контракта, кредитного договора
     *
     * @property \common\models\sbbolxml\response\IntCtrlStatementNumType $intCtrlStatementNum
     */
    private $intCtrlStatementNum = null;

    /**
     * Gets as intCtrlStatementNum
     *
     * Основные данные контракта, кредитного договора
     *
     * @return \common\models\sbbolxml\response\IntCtrlStatementNumType
     */
    public function getIntCtrlStatementNum()
    {
        return $this->intCtrlStatementNum;
    }

    /**
     * Sets a new intCtrlStatementNum
     *
     * Основные данные контракта, кредитного договора
     *
     * @param \common\models\sbbolxml\response\IntCtrlStatementNumType $intCtrlStatementNum
     * @return static
     */
    public function setIntCtrlStatementNum(\common\models\sbbolxml\response\IntCtrlStatementNumType $intCtrlStatementNum)
    {
        $this->intCtrlStatementNum = $intCtrlStatementNum;
        return $this;
    }


}

