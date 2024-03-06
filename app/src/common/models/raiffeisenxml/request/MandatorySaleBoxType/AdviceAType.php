<?php

namespace common\models\raiffeisenxml\request\MandatorySaleBoxType;

/**
 * Class representing AdviceAType
 */
class AdviceAType
{

    /**
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[] $doc
     */
    private $doc = [
        
    ];

    /**
     * Adds as doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType $doc
     */
    public function addToDoc(\common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType $doc)
    {
        $this->doc[] = $doc;
        return $this;
    }

    /**
     * isset doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDoc($index)
    {
        return isset($this->doc[$index]);
    }

    /**
     * unset doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDoc($index)
    {
        unset($this->doc[$index]);
    }

    /**
     * Gets as doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[]
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Уведомление о поступлении денежных средств на транзитный валютный счет
     *  (будет только ОДНО)
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[] $doc
     * @return static
     */
    public function setDoc(array $doc)
    {
        $this->doc = $doc;
        return $this;
    }


}

