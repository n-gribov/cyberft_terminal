<?php

namespace common\models\sbbolxml\response\BigFilesStatusType;

/**
 * Class representing ErrorsAType
 */
class ErrorsAType
{

    /**
     * @property \common\models\sbbolxml\response\BFErrorType[] $error
     */
    private $error = array(
        
    );

    /**
     * Adds as error
     *
     * @return static
     * @param \common\models\sbbolxml\response\BFErrorType $error
     */
    public function addToError(\common\models\sbbolxml\response\BFErrorType $error)
    {
        $this->error[] = $error;
        return $this;
    }

    /**
     * isset error
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetError($index)
    {
        return isset($this->error[$index]);
    }

    /**
     * unset error
     *
     * @param scalar $index
     * @return void
     */
    public function unsetError($index)
    {
        unset($this->error[$index]);
    }

    /**
     * Gets as error
     *
     * @return \common\models\sbbolxml\response\BFErrorType[]
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets a new error
     *
     * @param \common\models\sbbolxml\response\BFErrorType[] $error
     * @return static
     */
    public function setError(array $error)
    {
        $this->error = $error;
        return $this;
    }


}

