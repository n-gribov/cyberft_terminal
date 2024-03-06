<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RzkDictUpdateType
 *
 * Запрос репликации справочников СБК
 * XSD Type: RzkDictUpdate
 */
class RzkDictUpdateType extends DictType
{

    /**
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @property string $rzkId
     */
    private $rzkId = null;

    /**
     * Gets as rzkId
     *
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @return string
     */
    public function getRzkId()
    {
        return $this->rzkId;
    }

    /**
     * Sets a new rzkId
     *
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @param string $rzkId
     * @return static
     */
    public function setRzkId($rzkId)
    {
        $this->rzkId = $rzkId;
        return $this;
    }


}

