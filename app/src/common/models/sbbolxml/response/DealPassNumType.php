<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealPassNumType
 *
 * Номер паспорта сделки, разбитый по частям
 * XSD Type: DealPassNum
 */
class DealPassNumType
{

    /**
     * Первая часть номера паспорта сделки (8 цифр)
     *
     * @property string $firstPartPS
     */
    private $firstPartPS = null;

    /**
     * Вторая часть номера паспорта сделки (4 цифры)
     *
     * @property string $secondPartPS
     */
    private $secondPartPS = null;

    /**
     * Третья часть номера паспорта сделки (4 цифры)
     *
     * @property string $thirdPartPS
     */
    private $thirdPartPS = null;

    /**
     * Четвертая часть номера паспорта сделки (1 цифра)
     *
     * @property string $fourthPartPS
     */
    private $fourthPartPS = null;

    /**
     * Пятая часть номера паспорта сделки (1 цифра)
     *
     * @property string $fifthPartPS
     */
    private $fifthPartPS = null;

    /**
     * Дата паспорта сделки
     *
     * @property \DateTime $datePS
     */
    private $datePS = null;

    /**
     * Номер ПС (полный)
     *
     * @property string $numPsFull
     */
    private $numPsFull = null;

    /**
     * Gets as firstPartPS
     *
     * Первая часть номера паспорта сделки (8 цифр)
     *
     * @return string
     */
    public function getFirstPartPS()
    {
        return $this->firstPartPS;
    }

    /**
     * Sets a new firstPartPS
     *
     * Первая часть номера паспорта сделки (8 цифр)
     *
     * @param string $firstPartPS
     * @return static
     */
    public function setFirstPartPS($firstPartPS)
    {
        $this->firstPartPS = $firstPartPS;
        return $this;
    }

    /**
     * Gets as secondPartPS
     *
     * Вторая часть номера паспорта сделки (4 цифры)
     *
     * @return string
     */
    public function getSecondPartPS()
    {
        return $this->secondPartPS;
    }

    /**
     * Sets a new secondPartPS
     *
     * Вторая часть номера паспорта сделки (4 цифры)
     *
     * @param string $secondPartPS
     * @return static
     */
    public function setSecondPartPS($secondPartPS)
    {
        $this->secondPartPS = $secondPartPS;
        return $this;
    }

    /**
     * Gets as thirdPartPS
     *
     * Третья часть номера паспорта сделки (4 цифры)
     *
     * @return string
     */
    public function getThirdPartPS()
    {
        return $this->thirdPartPS;
    }

    /**
     * Sets a new thirdPartPS
     *
     * Третья часть номера паспорта сделки (4 цифры)
     *
     * @param string $thirdPartPS
     * @return static
     */
    public function setThirdPartPS($thirdPartPS)
    {
        $this->thirdPartPS = $thirdPartPS;
        return $this;
    }

    /**
     * Gets as fourthPartPS
     *
     * Четвертая часть номера паспорта сделки (1 цифра)
     *
     * @return string
     */
    public function getFourthPartPS()
    {
        return $this->fourthPartPS;
    }

    /**
     * Sets a new fourthPartPS
     *
     * Четвертая часть номера паспорта сделки (1 цифра)
     *
     * @param string $fourthPartPS
     * @return static
     */
    public function setFourthPartPS($fourthPartPS)
    {
        $this->fourthPartPS = $fourthPartPS;
        return $this;
    }

    /**
     * Gets as fifthPartPS
     *
     * Пятая часть номера паспорта сделки (1 цифра)
     *
     * @return string
     */
    public function getFifthPartPS()
    {
        return $this->fifthPartPS;
    }

    /**
     * Sets a new fifthPartPS
     *
     * Пятая часть номера паспорта сделки (1 цифра)
     *
     * @param string $fifthPartPS
     * @return static
     */
    public function setFifthPartPS($fifthPartPS)
    {
        $this->fifthPartPS = $fifthPartPS;
        return $this;
    }

    /**
     * Gets as datePS
     *
     * Дата паспорта сделки
     *
     * @return \DateTime
     */
    public function getDatePS()
    {
        return $this->datePS;
    }

    /**
     * Sets a new datePS
     *
     * Дата паспорта сделки
     *
     * @param \DateTime $datePS
     * @return static
     */
    public function setDatePS(\DateTime $datePS)
    {
        $this->datePS = $datePS;
        return $this;
    }

    /**
     * Gets as numPsFull
     *
     * Номер ПС (полный)
     *
     * @return string
     */
    public function getNumPsFull()
    {
        return $this->numPsFull;
    }

    /**
     * Sets a new numPsFull
     *
     * Номер ПС (полный)
     *
     * @param string $numPsFull
     * @return static
     */
    public function setNumPsFull($numPsFull)
    {
        $this->numPsFull = $numPsFull;
        return $this;
    }


}

