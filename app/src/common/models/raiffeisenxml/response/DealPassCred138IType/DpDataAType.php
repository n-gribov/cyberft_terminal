<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType;

/**
 * Class representing DpDataAType
 */
class DpDataAType
{

    /**
     * Первая часть номера
     *
     * @property string $part1
     */
    private $part1 = null;

    /**
     * Вторая часть номера
     *
     * @property string $part2
     */
    private $part2 = null;

    /**
     * Третья часть номера
     *
     * @property string $part3
     */
    private $part3 = null;

    /**
     * Четвертая часть номера
     *
     * @property string $part4
     */
    private $part4 = null;

    /**
     * Пятая часть номера
     *
     * @property string $part5
     */
    private $part5 = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $psDate
     */
    private $psDate = null;

    /**
     * Gets as part1
     *
     * Первая часть номера
     *
     * @return string
     */
    public function getPart1()
    {
        return $this->part1;
    }

    /**
     * Sets a new part1
     *
     * Первая часть номера
     *
     * @param string $part1
     * @return static
     */
    public function setPart1($part1)
    {
        $this->part1 = $part1;
        return $this;
    }

    /**
     * Gets as part2
     *
     * Вторая часть номера
     *
     * @return string
     */
    public function getPart2()
    {
        return $this->part2;
    }

    /**
     * Sets a new part2
     *
     * Вторая часть номера
     *
     * @param string $part2
     * @return static
     */
    public function setPart2($part2)
    {
        $this->part2 = $part2;
        return $this;
    }

    /**
     * Gets as part3
     *
     * Третья часть номера
     *
     * @return string
     */
    public function getPart3()
    {
        return $this->part3;
    }

    /**
     * Sets a new part3
     *
     * Третья часть номера
     *
     * @param string $part3
     * @return static
     */
    public function setPart3($part3)
    {
        $this->part3 = $part3;
        return $this;
    }

    /**
     * Gets as part4
     *
     * Четвертая часть номера
     *
     * @return string
     */
    public function getPart4()
    {
        return $this->part4;
    }

    /**
     * Sets a new part4
     *
     * Четвертая часть номера
     *
     * @param string $part4
     * @return static
     */
    public function setPart4($part4)
    {
        $this->part4 = $part4;
        return $this;
    }

    /**
     * Gets as part5
     *
     * Пятая часть номера
     *
     * @return string
     */
    public function getPart5()
    {
        return $this->part5;
    }

    /**
     * Sets a new part5
     *
     * Пятая часть номера
     *
     * @param string $part5
     * @return static
     */
    public function setPart5($part5)
    {
        $this->part5 = $part5;
        return $this;
    }

    /**
     * Gets as psDate
     *
     * Дата ПС
     *
     * @return \DateTime
     */
    public function getPsDate()
    {
        return $this->psDate;
    }

    /**
     * Sets a new psDate
     *
     * Дата ПС
     *
     * @param \DateTime $psDate
     * @return static
     */
    public function setPsDate(\DateTime $psDate)
    {
        $this->psDate = $psDate;
        return $this;
    }


}

