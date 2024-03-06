<?php

namespace common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType;

/**
 * Class representing BlockedQueuesAType
 */
class BlockedQueuesAType
{

    /**
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5).
     *  Указывается, если есть блокировка по очерёдности. Пример: значение 3
     *  означает, что заблокированы очерёдности 4 - 6
     *
     * @property int $queues
     */
    private $queues = null;

    /**
     * Основание ареста
     *
     * @property string $annotation
     */
    private $annotation = null;

    /**
     * Наименование органа, наложившего арест
     *
     * @property string $arrestedBy
     */
    private $arrestedBy = null;

    /**
     * Код налогового органа, наложившего арест
     *
     * @property string $arestedByNum
     */
    private $arestedByNum = null;

    /**
     * Период органичения
     *
     * @property \common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod
     */
    private $limitationPeriod = null;

    /**
     * Gets as queues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5).
     *  Указывается, если есть блокировка по очерёдности. Пример: значение 3
     *  означает, что заблокированы очерёдности 4 - 6
     *
     * @return int
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * Sets a new queues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5).
     *  Указывается, если есть блокировка по очерёдности. Пример: значение 3
     *  означает, что заблокированы очерёдности 4 - 6
     *
     * @param int $queues
     * @return static
     */
    public function setQueues($queues)
    {
        $this->queues = $queues;
        return $this;
    }

    /**
     * Gets as annotation
     *
     * Основание ареста
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Sets a new annotation
     *
     * Основание ареста
     *
     * @param string $annotation
     * @return static
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
        return $this;
    }

    /**
     * Gets as arrestedBy
     *
     * Наименование органа, наложившего арест
     *
     * @return string
     */
    public function getArrestedBy()
    {
        return $this->arrestedBy;
    }

    /**
     * Sets a new arrestedBy
     *
     * Наименование органа, наложившего арест
     *
     * @param string $arrestedBy
     * @return static
     */
    public function setArrestedBy($arrestedBy)
    {
        $this->arrestedBy = $arrestedBy;
        return $this;
    }

    /**
     * Gets as arestedByNum
     *
     * Код налогового органа, наложившего арест
     *
     * @return string
     */
    public function getArestedByNum()
    {
        return $this->arestedByNum;
    }

    /**
     * Sets a new arestedByNum
     *
     * Код налогового органа, наложившего арест
     *
     * @param string $arestedByNum
     * @return static
     */
    public function setArestedByNum($arestedByNum)
    {
        $this->arestedByNum = $arestedByNum;
        return $this;
    }

    /**
     * Gets as limitationPeriod
     *
     * Период органичения
     *
     * @return \common\models\raiffeisenxml\response\LimitationPeriodType
     */
    public function getLimitationPeriod()
    {
        return $this->limitationPeriod;
    }

    /**
     * Sets a new limitationPeriod
     *
     * Период органичения
     *
     * @param \common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod
     * @return static
     */
    public function setLimitationPeriod(\common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod)
    {
        $this->limitationPeriod = $limitationPeriod;
        return $this;
    }


}
