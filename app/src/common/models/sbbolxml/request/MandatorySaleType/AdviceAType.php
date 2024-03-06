<?php

namespace common\models\sbbolxml\request\MandatorySaleType;

/**
 * Class representing AdviceAType
 */
class AdviceAType
{

    /**
     * Уведомление о поступлении денежных средств на транзитный валютный счет (будет только ОДНО)
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\AdviceAType\DocAType $doc
     */
    private $doc = null;

    /**
     * Gets as doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет (будет только ОДНО)
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\AdviceAType\DocAType
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет (будет только ОДНО)
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\AdviceAType\DocAType $doc
     * @return static
     */
    public function setDoc(\common\models\sbbolxml\request\MandatorySaleType\AdviceAType\DocAType $doc)
    {
        $this->doc = $doc;
        return $this;
    }


}

