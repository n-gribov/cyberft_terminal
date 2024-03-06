<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CitizenshipType
 *
 *
 * XSD Type: Citizenship
 */
class CitizenshipType
{

    /**
     * Заполняется РОССИЯ
     *  Из-за форматов 1С. Там Гражданство от 1 до 64 - строка
     *  Если при импорте на стороне клиента не распарсим - будем считать, что другое государство
     *  (считаем РФ, если указано: РОССИЯ, РФ, Российская Федерация - список м.б. расширен)
     *
     * @property string $rF
     */
    private $rF = null;

    /**
     * Заполняется наименованием другой страны
     *
     * @property string $other
     */
    private $other = null;

    /**
     * Gets as rF
     *
     * Заполняется РОССИЯ
     *  Из-за форматов 1С. Там Гражданство от 1 до 64 - строка
     *  Если при импорте на стороне клиента не распарсим - будем считать, что другое государство
     *  (считаем РФ, если указано: РОССИЯ, РФ, Российская Федерация - список м.б. расширен)
     *
     * @return string
     */
    public function getRF()
    {
        return $this->rF;
    }

    /**
     * Sets a new rF
     *
     * Заполняется РОССИЯ
     *  Из-за форматов 1С. Там Гражданство от 1 до 64 - строка
     *  Если при импорте на стороне клиента не распарсим - будем считать, что другое государство
     *  (считаем РФ, если указано: РОССИЯ, РФ, Российская Федерация - список м.б. расширен)
     *
     * @param string $rF
     * @return static
     */
    public function setRF($rF)
    {
        $this->rF = $rF;
        return $this;
    }

    /**
     * Gets as other
     *
     * Заполняется наименованием другой страны
     *
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * Заполняется наименованием другой страны
     *
     * @param string $other
     * @return static
     */
    public function setOther($other)
    {
        $this->other = $other;
        return $this;
    }


}

