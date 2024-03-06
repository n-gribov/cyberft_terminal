<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing PayDocsCurAType
 */
class PayDocsCurAType
{

    /**
     * Поручение на перевод валюты
     *
     * @property \common\models\sbbolxml\response\RZKPayDocCurType[] $payDocCur
     */
    private $payDocCur = array(
        
    );

    /**
     * Adds as payDocCur
     *
     * Поручение на перевод валюты
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKPayDocCurType $payDocCur
     */
    public function addToPayDocCur(\common\models\sbbolxml\response\RZKPayDocCurType $payDocCur)
    {
        $this->payDocCur[] = $payDocCur;
        return $this;
    }

    /**
     * isset payDocCur
     *
     * Поручение на перевод валюты
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocCur($index)
    {
        return isset($this->payDocCur[$index]);
    }

    /**
     * unset payDocCur
     *
     * Поручение на перевод валюты
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocCur($index)
    {
        unset($this->payDocCur[$index]);
    }

    /**
     * Gets as payDocCur
     *
     * Поручение на перевод валюты
     *
     * @return \common\models\sbbolxml\response\RZKPayDocCurType[]
     */
    public function getPayDocCur()
    {
        return $this->payDocCur;
    }

    /**
     * Sets a new payDocCur
     *
     * Поручение на перевод валюты
     *
     * @param \common\models\sbbolxml\response\RZKPayDocCurType[] $payDocCur
     * @return static
     */
    public function setPayDocCur(array $payDocCur)
    {
        $this->payDocCur = $payDocCur;
        return $this;
    }


}

