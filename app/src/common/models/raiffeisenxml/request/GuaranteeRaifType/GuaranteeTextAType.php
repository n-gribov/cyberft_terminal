<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing GuaranteeTextAType
 */
class GuaranteeTextAType
{

    /**
     * Возможные значения "В соответствии с Вашим стандартным текстом", "Согласно прилагаемому образцу".
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Текст гарантии
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Gets as type
     *
     * Возможные значения "В соответствии с Вашим стандартным текстом", "Согласно прилагаемому образцу".
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Возможные значения "В соответствии с Вашим стандартным текстом", "Согласно прилагаемому образцу".
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as text
     *
     * Текст гарантии
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Текст гарантии
     *
     * @param string $text
     * @return static
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }


}

