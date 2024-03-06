<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing StateType
 *
 *
 * XSD Type: State
 */
class StateType
{

    /**
     * Код административной единицы
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Наименование административной единицы
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as code
     *
     * Код административной единицы
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код административной единицы
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование административной единицы
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование административной единицы
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

