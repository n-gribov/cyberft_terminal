<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtCommonDocType
 *
 * Общие реквизиты электронного документа
 * XSD Type: CorpCardExtCommonDoc
 */
class CorpCardExtCommonDocType
{

    /**
     * Эмбоссированный текст(Общий текст на карте)
     *
     * @property string $embossingMainText
     */
    private $embossingMainText = null;

    /**
     * Тип карты
     *
     * @property string $cardType
     */
    private $cardType = null;

    /**
     * Количество сотрудников
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * Gets as embossingMainText
     *
     * Эмбоссированный текст(Общий текст на карте)
     *
     * @return string
     */
    public function getEmbossingMainText()
    {
        return $this->embossingMainText;
    }

    /**
     * Sets a new embossingMainText
     *
     * Эмбоссированный текст(Общий текст на карте)
     *
     * @param string $embossingMainText
     * @return static
     */
    public function setEmbossingMainText($embossingMainText)
    {
        $this->embossingMainText = $embossingMainText;
        return $this;
    }

    /**
     * Gets as cardType
     *
     * Тип карты
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Sets a new cardType
     *
     * Тип карты
     *
     * @param string $cardType
     * @return static
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * Gets as total
     *
     * Количество сотрудников
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sets a new total
     *
     * Количество сотрудников
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }


}

