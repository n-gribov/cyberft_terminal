<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing DocIdsAType
 */
class DocIdsAType
{

    /**
     * @property \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[] $docId
     */
    private $docId = [
        
    ];

    /**
     * Adds as docId
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType $docId
     */
    public function addToDocId(\common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType $docId)
    {
        $this->docId[] = $docId;
        return $this;
    }

    /**
     * isset docId
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDocId($index)
    {
        return isset($this->docId[$index]);
    }

    /**
     * unset docId
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDocId($index)
    {
        unset($this->docId[$index]);
    }

    /**
     * Gets as docId
     *
     * @return \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[]
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * @param \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[] $docId
     * @return static
     */
    public function setDocId(array $docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

