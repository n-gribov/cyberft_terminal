<?php

namespace common\models\sbbolxml\request\RegOfIssCardsType;

/**
 * Class representing ListOfIssCardsAType
 */
class ListOfIssCardsAType
{

    /**
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[] $issCardInfo
     */
    private $issCardInfo = array(
        
    );

    /**
     * Adds as issCardInfo
     *
     * @return static
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType $issCardInfo
     */
    public function addToIssCardInfo(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType $issCardInfo)
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
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[]
     */
    public function getIssCardInfo()
    {
        return $this->issCardInfo;
    }

    /**
     * Sets a new issCardInfo
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[] $issCardInfo
     * @return static
     */
    public function setIssCardInfo(array $issCardInfo)
    {
        $this->issCardInfo = $issCardInfo;
        return $this;
    }


}

