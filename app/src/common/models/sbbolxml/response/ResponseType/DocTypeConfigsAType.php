<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing DocTypeConfigsAType
 */
class DocTypeConfigsAType
{

    /**
     * @property \common\models\sbbolxml\response\DocTypeConfigType[] $docTypeConfig
     */
    private $docTypeConfig = array(
        
    );

    /**
     * Adds as docTypeConfig
     *
     * @return static
     * @param \common\models\sbbolxml\response\DocTypeConfigType $docTypeConfig
     */
    public function addToDocTypeConfig(\common\models\sbbolxml\response\DocTypeConfigType $docTypeConfig)
    {
        $this->docTypeConfig[] = $docTypeConfig;
        return $this;
    }

    /**
     * isset docTypeConfig
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDocTypeConfig($index)
    {
        return isset($this->docTypeConfig[$index]);
    }

    /**
     * unset docTypeConfig
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDocTypeConfig($index)
    {
        unset($this->docTypeConfig[$index]);
    }

    /**
     * Gets as docTypeConfig
     *
     * @return \common\models\sbbolxml\response\DocTypeConfigType[]
     */
    public function getDocTypeConfig()
    {
        return $this->docTypeConfig;
    }

    /**
     * Sets a new docTypeConfig
     *
     * @param \common\models\sbbolxml\response\DocTypeConfigType[] $docTypeConfig
     * @return static
     */
    public function setDocTypeConfig(array $docTypeConfig)
    {
        $this->docTypeConfig = $docTypeConfig;
        return $this;
    }


}

