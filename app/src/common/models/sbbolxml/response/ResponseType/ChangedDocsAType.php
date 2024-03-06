<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing ChangedDocsAType
 */
class ChangedDocsAType
{

    /**
     * Идентификатор измененного документа
     *
     * @property string[] $docId
     */
    private $docId = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as docId
     *
     * Идентификатор измененного документа
     *
     * @return static
     * @param string $docId
     */
    public function addToDocId($docId)
    {
        $this->docId[] = $docId;
        return $this;
    }

    /**
     * isset docId
     *
     * Идентификатор измененного документа
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
     * Идентификатор измененного документа
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
     * Идентификатор измененного документа
     *
     * @return string[]
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор измененного документа
     *
     * @param string $docId
     * @return static
     */
    public function setDocId(array $docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

