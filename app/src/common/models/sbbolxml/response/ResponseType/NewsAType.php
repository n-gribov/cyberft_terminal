<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing NewsAType
 */
class NewsAType
{

    /**
     * @property \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[] $new
     */
    private $new = array(
        
    );

    /**
     * Adds as new
     *
     * @return static
     * @param \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType $new
     */
    public function addToNew(\common\models\sbbolxml\response\ResponseType\NewsAType\NewAType $new)
    {
        $this->new[] = $new;
        return $this;
    }

    /**
     * isset new
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetNew($index)
    {
        return isset($this->new[$index]);
    }

    /**
     * unset new
     *
     * @param scalar $index
     * @return void
     */
    public function unsetNew($index)
    {
        unset($this->new[$index]);
    }

    /**
     * Gets as new
     *
     * @return \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[]
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Sets a new new
     *
     * @param \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[] $new
     * @return static
     */
    public function setNew(array $new)
    {
        $this->new = $new;
        return $this;
    }


}

