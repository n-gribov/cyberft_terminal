<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing UserRestrictionType
 *
 * Ограничение учетной записи
 * XSD Type: UserRestriction
 */
class UserRestrictionType
{

    /**
     * Тип ограничения
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Уникальный ID ограничения
     *
     * @property string $replicationGUID
     */
    private $replicationGUID = null;

    /**
     * Информация о счете. Передается информация
     *  только о
     *  подключенных счетах данному уполномоченному лицу
     *
     * @property \common\models\sbbolxml\response\UserRestrictionType\AccountAType $account
     */
    private $account = null;

    /**
     * Gets as type
     *
     * Тип ограничения
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип ограничения
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as replicationGUID
     *
     * Уникальный ID ограничения
     *
     * @return string
     */
    public function getReplicationGUID()
    {
        return $this->replicationGUID;
    }

    /**
     * Sets a new replicationGUID
     *
     * Уникальный ID ограничения
     *
     * @param string $replicationGUID
     * @return static
     */
    public function setReplicationGUID($replicationGUID)
    {
        $this->replicationGUID = $replicationGUID;
        return $this;
    }

    /**
     * Gets as account
     *
     * Информация о счете. Передается информация
     *  только о
     *  подключенных счетах данному уполномоченному лицу
     *
     * @return \common\models\sbbolxml\response\UserRestrictionType\AccountAType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Информация о счете. Передается информация
     *  только о
     *  подключенных счетах данному уполномоченному лицу
     *
     * @param \common\models\sbbolxml\response\UserRestrictionType\AccountAType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\response\UserRestrictionType\AccountAType $account)
    {
        $this->account = $account;
        return $this;
    }


}

