<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType;

/**
 * Class representing AccreditiveTypeAType
 */
class AccreditiveTypeAType
{

    /**
     * Безотзывной. 0 - нет, 1 - да.
     *
     * @property bool $irrevocable
     */
    private $irrevocable = null;

    /**
     * Переводной. 0 - нет, 1 - да.
     *
     * @property bool $transferable
     */
    private $transferable = null;

    /**
     * Безотзывной и подтвержденный. 0 - нет, 1 - да.
     *
     * @property bool $irrecvocableConfirmed
     */
    private $irrecvocableConfirmed = null;

    /**
     * Резервный. 0 - нет, 1 - да.
     *
     * @property bool $standBy
     */
    private $standBy = null;

    /**
     * Gets as irrevocable
     *
     * Безотзывной. 0 - нет, 1 - да.
     *
     * @return bool
     */
    public function getIrrevocable()
    {
        return $this->irrevocable;
    }

    /**
     * Sets a new irrevocable
     *
     * Безотзывной. 0 - нет, 1 - да.
     *
     * @param bool $irrevocable
     * @return static
     */
    public function setIrrevocable($irrevocable)
    {
        $this->irrevocable = $irrevocable;
        return $this;
    }

    /**
     * Gets as transferable
     *
     * Переводной. 0 - нет, 1 - да.
     *
     * @return bool
     */
    public function getTransferable()
    {
        return $this->transferable;
    }

    /**
     * Sets a new transferable
     *
     * Переводной. 0 - нет, 1 - да.
     *
     * @param bool $transferable
     * @return static
     */
    public function setTransferable($transferable)
    {
        $this->transferable = $transferable;
        return $this;
    }

    /**
     * Gets as irrecvocableConfirmed
     *
     * Безотзывной и подтвержденный. 0 - нет, 1 - да.
     *
     * @return bool
     */
    public function getIrrecvocableConfirmed()
    {
        return $this->irrecvocableConfirmed;
    }

    /**
     * Sets a new irrecvocableConfirmed
     *
     * Безотзывной и подтвержденный. 0 - нет, 1 - да.
     *
     * @param bool $irrecvocableConfirmed
     * @return static
     */
    public function setIrrecvocableConfirmed($irrecvocableConfirmed)
    {
        $this->irrecvocableConfirmed = $irrecvocableConfirmed;
        return $this;
    }

    /**
     * Gets as standBy
     *
     * Резервный. 0 - нет, 1 - да.
     *
     * @return bool
     */
    public function getStandBy()
    {
        return $this->standBy;
    }

    /**
     * Sets a new standBy
     *
     * Резервный. 0 - нет, 1 - да.
     *
     * @param bool $standBy
     * @return static
     */
    public function setStandBy($standBy)
    {
        $this->standBy = $standBy;
        return $this;
    }


}

