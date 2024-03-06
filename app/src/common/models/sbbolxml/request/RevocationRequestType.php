<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RevocationRequestType
 *
 *
 * XSD Type: RevocationRequest
 */
class RevocationRequestType extends DocBaseType
{

    /**
     * @property \common\models\sbbolxml\request\RevocationRequestType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Связанный документ
     *
     * @property \common\models\sbbolxml\request\RevocationRequestType\LinkedDocAType $linkedDoc
     */
    private $linkedDoc = null;

    /**
     * Gets as docData
     *
     * @return \common\models\sbbolxml\request\RevocationRequestType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\sbbolxml\request\RevocationRequestType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\RevocationRequestType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as linkedDoc
     *
     * Связанный документ
     *
     * @return \common\models\sbbolxml\request\RevocationRequestType\LinkedDocAType
     */
    public function getLinkedDoc()
    {
        return $this->linkedDoc;
    }

    /**
     * Sets a new linkedDoc
     *
     * Связанный документ
     *
     * @param \common\models\sbbolxml\request\RevocationRequestType\LinkedDocAType $linkedDoc
     * @return static
     */
    public function setLinkedDoc(\common\models\sbbolxml\request\RevocationRequestType\LinkedDocAType $linkedDoc)
    {
        $this->linkedDoc = $linkedDoc;
        return $this;
    }


}

