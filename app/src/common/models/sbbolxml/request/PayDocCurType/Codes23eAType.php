<?php

namespace common\models\sbbolxml\request\PayDocCurType;

/**
 * Class representing Codes23eAType
 *
 * Коды инструкций 23е
 */
class Codes23eAType
{

    /**
     * Код инструкций 23е
     *
     * @property \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[] $code23e
     */
    private $code23e = array(
        
    );

    /**
     * Adds as code23e
     *
     * Код инструкций 23е
     *
     * @return static
     * @param \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType $code23e
     */
    public function addToCode23e(\common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType $code23e)
    {
        $this->code23e[] = $code23e;
        return $this;
    }

    /**
     * isset code23e
     *
     * Код инструкций 23е
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCode23e($index)
    {
        return isset($this->code23e[$index]);
    }

    /**
     * unset code23e
     *
     * Код инструкций 23е
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCode23e($index)
    {
        unset($this->code23e[$index]);
    }

    /**
     * Gets as code23e
     *
     * Код инструкций 23е
     *
     * @return \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[]
     */
    public function getCode23e()
    {
        return $this->code23e;
    }

    /**
     * Sets a new code23e
     *
     * Код инструкций 23е
     *
     * @param \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[] $code23e
     * @return static
     */
    public function setCode23e(array $code23e)
    {
        $this->code23e = $code23e;
        return $this;
    }


}

