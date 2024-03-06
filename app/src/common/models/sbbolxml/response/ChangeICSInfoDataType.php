<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ChangeICSInfoDataType
 *
 * Сведения о результатах внесения изменений в одну Ведомость банковского контроля
 * XSD Type: ChangeICSInfoData
 */
class ChangeICSInfoDataType
{

    /**
     * Уникальный номер контракта (кредитного договора)
     *
     * @property string $iCSNum
     */
    private $iCSNum = null;

    /**
     * Результат обработки ВБК: 1 - Внесены изменения, 0 - Отказ во внесении изменений
     *
     * @property boolean $result
     */
    private $result = null;

    /**
     * Причина отказа во внесении изменений
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Gets as iCSNum
     *
     * Уникальный номер контракта (кредитного договора)
     *
     * @return string
     */
    public function getICSNum()
    {
        return $this->iCSNum;
    }

    /**
     * Sets a new iCSNum
     *
     * Уникальный номер контракта (кредитного договора)
     *
     * @param string $iCSNum
     * @return static
     */
    public function setICSNum($iCSNum)
    {
        $this->iCSNum = $iCSNum;
        return $this;
    }

    /**
     * Gets as result
     *
     * Результат обработки ВБК: 1 - Внесены изменения, 0 - Отказ во внесении изменений
     *
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets a new result
     *
     * Результат обработки ВБК: 1 - Внесены изменения, 0 - Отказ во внесении изменений
     *
     * @param boolean $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Gets as comment
     *
     * Причина отказа во внесении изменений
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets a new comment
     *
     * Причина отказа во внесении изменений
     *
     * @param string $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }


}

