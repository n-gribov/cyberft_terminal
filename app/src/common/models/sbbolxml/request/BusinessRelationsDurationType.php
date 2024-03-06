<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BusinessRelationsDurationType
 *
 * Планируемая длительность отношений
 * XSD Type: BusinessRelationsDuration
 */
class BusinessRelationsDurationType
{

    /**
     * Краткосрочная (до 1 года)
     *
     * @property boolean $shortTerm
     */
    private $shortTerm = null;

    /**
     * Долгосрочная (1 год и более)
     *
     * @property boolean $longTerm
     */
    private $longTerm = null;

    /**
     * Gets as shortTerm
     *
     * Краткосрочная (до 1 года)
     *
     * @return boolean
     */
    public function getShortTerm()
    {
        return $this->shortTerm;
    }

    /**
     * Sets a new shortTerm
     *
     * Краткосрочная (до 1 года)
     *
     * @param boolean $shortTerm
     * @return static
     */
    public function setShortTerm($shortTerm)
    {
        $this->shortTerm = $shortTerm;
        return $this;
    }

    /**
     * Gets as longTerm
     *
     * Долгосрочная (1 год и более)
     *
     * @return boolean
     */
    public function getLongTerm()
    {
        return $this->longTerm;
    }

    /**
     * Sets a new longTerm
     *
     * Долгосрочная (1 год и более)
     *
     * @param boolean $longTerm
     * @return static
     */
    public function setLongTerm($longTerm)
    {
        $this->longTerm = $longTerm;
        return $this;
    }


}

