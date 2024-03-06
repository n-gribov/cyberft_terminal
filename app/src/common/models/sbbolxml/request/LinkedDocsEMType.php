<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing LinkedDocsEMType
 *
 * Связанные документы
 * XSD Type: LinkedDocsEM
 */
class LinkedDocsEMType
{

    /**
     * Документ, связанный с поручением
     *
     * @property \common\models\sbbolxml\request\LinkedDocsEMType\LDocAType $lDoc
     */
    private $lDoc = null;

    /**
     * Gets as lDoc
     *
     * Документ, связанный с поручением
     *
     * @return \common\models\sbbolxml\request\LinkedDocsEMType\LDocAType
     */
    public function getLDoc()
    {
        return $this->lDoc;
    }

    /**
     * Sets a new lDoc
     *
     * Документ, связанный с поручением
     *
     * @param \common\models\sbbolxml\request\LinkedDocsEMType\LDocAType $lDoc
     * @return static
     */
    public function setLDoc(\common\models\sbbolxml\request\LinkedDocsEMType\LDocAType $lDoc)
    {
        $this->lDoc = $lDoc;
        return $this;
    }


}

