<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ImplRegOfIssCardsType
 *
 *
 * XSD Type: ImplRegOfIssCards
 */
class ImplRegOfIssCardsType
{

    /**
     * @property string $annotation
     */
    private $annotation = null;

    /**
     * @property \common\models\sbbolxml\response\IssCardInfoType[] $issCardInfo
     */
    private $issCardInfo = array(
        
    );

    /**
     * Gets as annotation
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
     * @param string $annotation
     * @return static
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
        return $this;
    }

    /**
     * Adds as issCardInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\IssCardInfoType $issCardInfo
     */
    public function addToIssCardInfo(\common\models\sbbolxml\response\IssCardInfoType $issCardInfo)
    {
        $this->issCardInfo[] = $issCardInfo;
        return $this;
    }

    /**
     * isset issCardInfo
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetIssCardInfo($index)
    {
        return isset($this->issCardInfo[$index]);
    }

    /**
     * unset issCardInfo
     *
     * @param scalar $index
     * @return void
     */
    public function unsetIssCardInfo($index)
    {
        unset($this->issCardInfo[$index]);
    }

    /**
     * Gets as issCardInfo
     *
     * @return \common\models\sbbolxml\response\IssCardInfoType[]
     */
    public function getIssCardInfo()
    {
        return $this->issCardInfo;
    }

    /**
     * Sets a new issCardInfo
     *
     * @param \common\models\sbbolxml\response\IssCardInfoType[] $issCardInfo
     * @return static
     */
    public function setIssCardInfo(array $issCardInfo)
    {
        $this->issCardInfo = $issCardInfo;
        return $this;
    }


}

