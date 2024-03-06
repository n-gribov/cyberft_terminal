<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing DocIdsAType
 */
class DocIdsAType
{

    /**
     * @property \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[] $docId
     */
    private $docId = array(
        
    );

    /**
     * Adds as docId
     *
     * @return static
     * @param \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType $docId
     */
    public function addToDocId(\common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType $docId)
    {
        $this->docId[] = $docId;
        return $this;
    }

    /**
     * isset docId
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDocId($index)
    {
        return isset($this->docId[$index]);
    }

    /**
     * unset docId
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDocId($index)
    {
        unset($this->docId[$index]);
    }

    /**
     * Gets as docId
     *
     * @return \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[]
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * @param \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[] $docId
     * @return static
     */
    public function setDocId(array $docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

