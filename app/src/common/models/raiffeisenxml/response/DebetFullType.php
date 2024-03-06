<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DebetFullType
 *
 *
 * XSD Type: DebetFull
 */
class DebetFullType
{

    /**
     * 1 - на счёт наложена полная блокировка дебету. 0 -полной блокировки по дебету нет.
     *
     * @property bool $check
     */
    private $check = null;

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
     * Дата начала действия ограничения
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата снятия ограничения
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Аресты по счету (Блокировки по сумме)
     *
     * @property \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSumInfo
     */
    private $blockedSumInfo = null;

    /**
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @property \common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[] $blockedQueuesInfo
     */
    private $blockedQueuesInfo = null;

    /**
     * Приостановления операций по счету выше очердности на сумму
     *
     * @property \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[] $blockedSumQueuesInfo
     */
    private $blockedSumQueuesInfo = null;

    /**
     * Gets as check
     *
     * 1 - на счёт наложена полная блокировка дебету. 0 -полной блокировки по дебету нет.
     *
     * @return bool
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * Sets a new check
     *
     * 1 - на счёт наложена полная блокировка дебету. 0 -полной блокировки по дебету нет.
     *
     * @param bool $check
     * @return static
     */
    public function setCheck($check)
    {
        $this->check = $check;
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
     * Gets as beginDate
     *
     * Дата начала действия ограничения
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала действия ограничения
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата снятия ограничения
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата снятия ограничения
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Adds as blockedSum
     *
     * Аресты по счету (Блокировки по сумме)
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum
     */
    public function addToBlockedSumInfo(\common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum)
    {
        $this->blockedSumInfo[] = $blockedSum;
        return $this;
    }

    /**
     * isset blockedSumInfo
     *
     * Аресты по счету (Блокировки по сумме)
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBlockedSumInfo($index)
    {
        return isset($this->blockedSumInfo[$index]);
    }

    /**
     * unset blockedSumInfo
     *
     * Аресты по счету (Блокировки по сумме)
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBlockedSumInfo($index)
    {
        unset($this->blockedSumInfo[$index]);
    }

    /**
     * Gets as blockedSumInfo
     *
     * Аресты по счету (Блокировки по сумме)
     *
     * @return \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[]
     */
    public function getBlockedSumInfo()
    {
        return $this->blockedSumInfo;
    }

    /**
     * Sets a new blockedSumInfo
     *
     * Аресты по счету (Блокировки по сумме)
     *
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSumInfo
     * @return static
     */
    public function setBlockedSumInfo(array $blockedSumInfo)
    {
        $this->blockedSumInfo = $blockedSumInfo;
        return $this;
    }

    /**
     * Adds as blockedQueues
     *
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType $blockedQueues
     */
    public function addToBlockedQueuesInfo(\common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType $blockedQueues)
    {
        $this->blockedQueuesInfo[] = $blockedQueues;
        return $this;
    }

    /**
     * isset blockedQueuesInfo
     *
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBlockedQueuesInfo($index)
    {
        return isset($this->blockedQueuesInfo[$index]);
    }

    /**
     * unset blockedQueuesInfo
     *
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBlockedQueuesInfo($index)
    {
        unset($this->blockedQueuesInfo[$index]);
    }

    /**
     * Gets as blockedQueuesInfo
     *
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @return \common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[]
     */
    public function getBlockedQueuesInfo()
    {
        return $this->blockedQueuesInfo;
    }

    /**
     * Sets a new blockedQueuesInfo
     *
     * Приотстановление опреаций по счету выше очередности(блокировки по очередности)
     *
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[] $blockedQueuesInfo
     * @return static
     */
    public function setBlockedQueuesInfo(array $blockedQueuesInfo)
    {
        $this->blockedQueuesInfo = $blockedQueuesInfo;
        return $this;
    }

    /**
     * Adds as blockedSumQueues
     *
     * Приостановления операций по счету выше очердности на сумму
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\BlockedSumQueuesFullType $blockedSumQueues
     */
    public function addToBlockedSumQueuesInfo(\common\models\raiffeisenxml\response\BlockedSumQueuesFullType $blockedSumQueues)
    {
        $this->blockedSumQueuesInfo[] = $blockedSumQueues;
        return $this;
    }

    /**
     * isset blockedSumQueuesInfo
     *
     * Приостановления операций по счету выше очердности на сумму
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBlockedSumQueuesInfo($index)
    {
        return isset($this->blockedSumQueuesInfo[$index]);
    }

    /**
     * unset blockedSumQueuesInfo
     *
     * Приостановления операций по счету выше очердности на сумму
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBlockedSumQueuesInfo($index)
    {
        unset($this->blockedSumQueuesInfo[$index]);
    }

    /**
     * Gets as blockedSumQueuesInfo
     *
     * Приостановления операций по счету выше очердности на сумму
     *
     * @return \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[]
     */
    public function getBlockedSumQueuesInfo()
    {
        return $this->blockedSumQueuesInfo;
    }

    /**
     * Sets a new blockedSumQueuesInfo
     *
     * Приостановления операций по счету выше очердности на сумму
     *
     * @param \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[] $blockedSumQueuesInfo
     * @return static
     */
    public function setBlockedSumQueuesInfo(array $blockedSumQueuesInfo)
    {
        $this->blockedSumQueuesInfo = $blockedSumQueuesInfo;
        return $this;
    }


}

