<?php

namespace common\models\sbbolxml\request\BenefAddType;

/**
 * Class representing OptionsAType
 */
class OptionsAType
{

    /**
     * Опция "A" или "D" для поля 56а
     *
     * @property string $option56a
     */
    private $option56a = null;

    /**
     * Опция A или D для поля 57а
     *
     * @property string $option57a
     */
    private $option57a = null;

    /**
     * Опция "A" для поля 59а
     *
     * @property string $option59a
     */
    private $option59a = null;

    /**
     * Gets as option56a
     *
     * Опция "A" или "D" для поля 56а
     *
     * @return string
     */
    public function getOption56a()
    {
        return $this->option56a;
    }

    /**
     * Sets a new option56a
     *
     * Опция "A" или "D" для поля 56а
     *
     * @param string $option56a
     * @return static
     */
    public function setOption56a($option56a)
    {
        $this->option56a = $option56a;
        return $this;
    }

    /**
     * Gets as option57a
     *
     * Опция A или D для поля 57а
     *
     * @return string
     */
    public function getOption57a()
    {
        return $this->option57a;
    }

    /**
     * Sets a new option57a
     *
     * Опция A или D для поля 57а
     *
     * @param string $option57a
     * @return static
     */
    public function setOption57a($option57a)
    {
        $this->option57a = $option57a;
        return $this;
    }

    /**
     * Gets as option59a
     *
     * Опция "A" для поля 59а
     *
     * @return string
     */
    public function getOption59a()
    {
        return $this->option59a;
    }

    /**
     * Sets a new option59a
     *
     * Опция "A" для поля 59а
     *
     * @param string $option59a
     * @return static
     */
    public function setOption59a($option59a)
    {
        $this->option59a = $option59a;
        return $this;
    }


}

