<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BlockedSumQueuesFullType
 *
 *
 * XSD Type: BlockedSumQueuesFull
 */
class BlockedSumQueuesFullType
{

    /**
     * Сумма блокировки
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается, если есть
     *  блокировка по очерёдности. Пример: значение 3 означает, что заблокированы очерёдности 4 - 6
     *
     * @property integer $queues
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
     * @property \common\models\sbbolxml\response\LimitationPeriodType $limitationPeriod
     */
    private $limitationPeriod = null;

    /**
     * Gets as sum
     *
     * Сумма блокировки
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма блокировки
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as queues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается, если есть
     *  блокировка по очерёдности. Пример: значение 3 означает, что заблокированы очерёдности 4 - 6
     *
     * @return integer
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * Sets a new queues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается, если есть
     *  блокировка по очерёдности. Пример: значение 3 означает, что заблокированы очерёдности 4 - 6
     *
     * @param integer $queues
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
     * @return \common\models\sbbolxml\response\LimitationPeriodType
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
     * @param \common\models\sbbolxml\response\LimitationPeriodType $limitationPeriod
     * @return static
     */
    public function setLimitationPeriod(\common\models\sbbolxml\response\LimitationPeriodType $limitationPeriod)
    {
        $this->limitationPeriod = $limitationPeriod;
        return $this;
    }


}

