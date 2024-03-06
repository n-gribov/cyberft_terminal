<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CommisionType
 *
 *
 * XSD Type: Commision
 */
class CommisionType
{

    /**
     * Реквизиты счёта списания комиссионного вознаграждения
     *
     * @property \common\models\sbbolxml\request\ComAccType $comAcc
     */
    private $comAcc = null;

    /**
     * Реквизиты платёжного поручения, которым будет перечислено комиссионное
     *  вознаграждение
     *
     * @property \common\models\sbbolxml\request\ComOrderType $comOrder
     */
    private $comOrder = null;

    /**
     * Gets as comAcc
     *
     * Реквизиты счёта списания комиссионного вознаграждения
     *
     * @return \common\models\sbbolxml\request\ComAccType
     */
    public function getComAcc()
    {
        return $this->comAcc;
    }

    /**
     * Sets a new comAcc
     *
     * Реквизиты счёта списания комиссионного вознаграждения
     *
     * @param \common\models\sbbolxml\request\ComAccType $comAcc
     * @return static
     */
    public function setComAcc(\common\models\sbbolxml\request\ComAccType $comAcc)
    {
        $this->comAcc = $comAcc;
        return $this;
    }

    /**
     * Gets as comOrder
     *
     * Реквизиты платёжного поручения, которым будет перечислено комиссионное
     *  вознаграждение
     *
     * @return \common\models\sbbolxml\request\ComOrderType
     */
    public function getComOrder()
    {
        return $this->comOrder;
    }

    /**
     * Sets a new comOrder
     *
     * Реквизиты платёжного поручения, которым будет перечислено комиссионное
     *  вознаграждение
     *
     * @param \common\models\sbbolxml\request\ComOrderType $comOrder
     * @return static
     */
    public function setComOrder(\common\models\sbbolxml\request\ComOrderType $comOrder)
    {
        $this->comOrder = $comOrder;
        return $this;
    }


}

