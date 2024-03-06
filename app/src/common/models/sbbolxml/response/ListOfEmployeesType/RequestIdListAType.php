<?php

namespace common\models\sbbolxml\response\ListOfEmployeesType;

/**
 * Class representing RequestIdListAType
 *
 * Список тикетов для запроса следующих частей списка сотрудников
 */
class RequestIdListAType
{

    /**
     * @property string[] $requestId
     */
    private $requestId = array(
        
    );

    /**
     * Adds as requestId
     *
     * @return static
     * @param string $requestId
     */
    public function addToRequestId($requestId)
    {
        $this->requestId[] = $requestId;
        return $this;
    }

    /**
     * isset requestId
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRequestId($index)
    {
        return isset($this->requestId[$index]);
    }

    /**
     * unset requestId
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRequestId($index)
    {
        unset($this->requestId[$index]);
    }

    /**
     * Gets as requestId
     *
     * @return string[]
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Sets a new requestId
     *
     * @param string $requestId
     * @return static
     */
    public function setRequestId(array $requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }


}

