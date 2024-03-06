<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing CorrespondentAType
 */
class CorrespondentAType
{

    /**
     * Уникальный идентификатор корреспондента
     *
     * @property string $correspUUID
     */
    private $correspUUID = null;

    /**
     * Gets as correspUUID
     *
     * Уникальный идентификатор корреспондента
     *
     * @return string
     */
    public function getCorrespUUID()
    {
        return $this->correspUUID;
    }

    /**
     * Sets a new correspUUID
     *
     * Уникальный идентификатор корреспондента
     *
     * @param string $correspUUID
     * @return static
     */
    public function setCorrespUUID($correspUUID)
    {
        $this->correspUUID = $correspUUID;
        return $this;
    }


}

