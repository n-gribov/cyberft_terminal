<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RzkPayDocsRuType
 *
 * Запрос рублевых платежных поручений
 * XSD Type: RzkPayDocsRu
 */
class RzkPayDocsRuType
{

    /**
     * Дата и время последнего запроса рублевых платежных поручений(с час. поясами)
     *
     * @property \DateTime $lastRequestTime
     */
    private $lastRequestTime = null;

    /**
     * Gets as lastRequestTime
     *
     * Дата и время последнего запроса рублевых платежных поручений(с час. поясами)
     *
     * @return \DateTime
     */
    public function getLastRequestTime()
    {
        return $this->lastRequestTime;
    }

    /**
     * Sets a new lastRequestTime
     *
     * Дата и время последнего запроса рублевых платежных поручений(с час. поясами)
     *
     * @param \DateTime $lastRequestTime
     * @return static
     */
    public function setLastRequestTime(\DateTime $lastRequestTime)
    {
        $this->lastRequestTime = $lastRequestTime;
        return $this;
    }


}

