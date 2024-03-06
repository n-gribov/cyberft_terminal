<?php

namespace common\models\sbbolxml\response\DebetFullType;

/**
 * Class representing BlockedQueuesInfoAType
 */
class BlockedQueuesInfoAType
{

    /**
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @property \common\models\sbbolxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[] $blockedQueues
     */
    private $blockedQueues = array(
        
    );

    /**
     * Adds as blockedQueues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @return static
     * @param \common\models\sbbolxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType $blockedQueues
     */
    public function addToBlockedQueues(\common\models\sbbolxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType $blockedQueues)
    {
        $this->blockedQueues[] = $blockedQueues;
        return $this;
    }

    /**
     * isset blockedQueues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBlockedQueues($index)
    {
        return isset($this->blockedQueues[$index]);
    }

    /**
     * unset blockedQueues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBlockedQueues($index)
    {
        unset($this->blockedQueues[$index]);
    }

    /**
     * Gets as blockedQueues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @return \common\models\sbbolxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[]
     */
    public function getBlockedQueues()
    {
        return $this->blockedQueues;
    }

    /**
     * Sets a new blockedQueues
     *
     * Наибольшая разрешённая очерёдность платежей (от 1 до 5). Указывается,
     *  если есть блокировка по очерёдности. Пример: значение 3 означает, что заблокированы
     *  очерёдности 4 - 6
     *
     * @param \common\models\sbbolxml\response\DebetFullType\BlockedQueuesInfoAType\BlockedQueuesAType[] $blockedQueues
     * @return static
     */
    public function setBlockedQueues(array $blockedQueues)
    {
        $this->blockedQueues = $blockedQueues;
        return $this;
    }


}

