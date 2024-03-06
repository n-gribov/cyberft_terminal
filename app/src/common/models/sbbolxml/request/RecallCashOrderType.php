<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RecallCashOrderType
 *
 * Отмена заявки на получение наличных средств
 * XSD Type: RecallCashOrder
 */
class RecallCashOrderType extends DocBaseType
{

    /**
     * Идентификатор отменяемого документа
     *
     * @property string $recallDocRef
     */
    private $recallDocRef = null;

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataRecallCashOrderType $docDataRecallCashOrder
     */
    private $docDataRecallCashOrder = null;

    /**
     * Gets as recallDocRef
     *
     * Идентификатор отменяемого документа
     *
     * @return string
     */
    public function getRecallDocRef()
    {
        return $this->recallDocRef;
    }

    /**
     * Sets a new recallDocRef
     *
     * Идентификатор отменяемого документа
     *
     * @param string $recallDocRef
     * @return static
     */
    public function setRecallDocRef($recallDocRef)
    {
        $this->recallDocRef = $recallDocRef;
        return $this;
    }

    /**
     * Gets as docDataRecallCashOrder
     *
     * Реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataRecallCashOrderType
     */
    public function getDocDataRecallCashOrder()
    {
        return $this->docDataRecallCashOrder;
    }

    /**
     * Sets a new docDataRecallCashOrder
     *
     * Реквизиты документа
     *
     * @param \common\models\sbbolxml\request\DocDataRecallCashOrderType $docDataRecallCashOrder
     * @return static
     */
    public function setDocDataRecallCashOrder(\common\models\sbbolxml\request\DocDataRecallCashOrderType $docDataRecallCashOrder)
    {
        $this->docDataRecallCashOrder = $docDataRecallCashOrder;
        return $this;
    }


}

