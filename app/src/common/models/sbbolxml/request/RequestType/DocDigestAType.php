<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing DocDigestAType
 */
class DocDigestAType
{

    /**
     * @property \common\models\sbbolxml\request\RequestType\DocDigestAType\DocIdAType $docId
     */
    private $docId = null;

    /**
     * Gets as docId
     *
     * @return \common\models\sbbolxml\request\RequestType\DocDigestAType\DocIdAType
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * @param \common\models\sbbolxml\request\RequestType\DocDigestAType\DocIdAType $docId
     * @return static
     */
    public function setDocId(\common\models\sbbolxml\request\RequestType\DocDigestAType\DocIdAType $docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

