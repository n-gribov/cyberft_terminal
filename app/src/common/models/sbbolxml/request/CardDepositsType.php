<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CardDepositsType
 *
 * Запрос списка карточек депозитов организации клиента
 * XSD Type: CardDeposits
 */
class CardDepositsType
{

    /**
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна полная репликация справочника
     *
     * @property integer $stepId
     */
    private $stepId = null;

    /**
     * Gets as stepId
     *
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна полная репликация справочника
     *
     * @return integer
     */
    public function getStepId()
    {
        return $this->stepId;
    }

    /**
     * Sets a new stepId
     *
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна полная репликация справочника
     *
     * @param integer $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }


}
