<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DPEndDataType
 *
 * Сведения об оформлении, переводе и закрытии паспорта сделки
 * XSD Type: DPEndData
 */
class DPEndDataType
{

    /**
     * Регистрационный номер банка ПС
     *
     * @property string $numBankPS
     */
    private $numBankPS = null;

    /**
     * Дата принятия ПС при его переводе
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Дата закрытия ПС
     *
     * @property \DateTime $dPEndDate
     */
    private $dPEndDate = null;

    /**
     * Код основания закрытия ПС:
     *  7.1.1 - перевод в другой банк, закрытие всех счетов в банке
     *  7.1.2 - исполнение сторонами всех обязательств по контракту (кредитному договору), включая исполнение обязательств третьим лицом - резидентом (другим лицом - резидентом)
     *  7.1.3 - уступка резидентом требования по контракту (кредитному договору) другому лицу - резиденту либо перевод долга резидентом по контракту (кредитному договору) на другое лицо - резидента
     *  7.1.4 - уступка резидентом требования по контракту (кредитному договору) нерезиденту
     *  7.1.5 - исполнение (прекращение) обязательств по контракту (кредитному договору) по иным основаниям
     *  7.1.6 - прекращение оснований, требующих оформления ПС, ошибочное,
     *  20.4 оформление ПС
     *  7.9 - по истечении 180 дней, следующих за датой завершения исполнения обязательств
     *
     * @property string $baseEndPS
     */
    private $baseEndPS = null;

    /**
     * Результат обработки ПС: 1 - закрыт, 0 - отказ в закрытии
     *
     * @property boolean $result
     */
    private $result = null;

    /**
     * Причина отказа
     *
     * @property \common\models\sbbolxml\response\CaseType $formalCause
     */
    private $formalCause = null;

    /**
     * Gets as numBankPS
     *
     * Регистрационный номер банка ПС
     *
     * @return string
     */
    public function getNumBankPS()
    {
        return $this->numBankPS;
    }

    /**
     * Sets a new numBankPS
     *
     * Регистрационный номер банка ПС
     *
     * @param string $numBankPS
     * @return static
     */
    public function setNumBankPS($numBankPS)
    {
        $this->numBankPS = $numBankPS;
        return $this;
    }

    /**
     * Gets as valueDate
     *
     * Дата принятия ПС при его переводе
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Дата принятия ПС при его переводе
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
        return $this;
    }

    /**
     * Gets as dPEndDate
     *
     * Дата закрытия ПС
     *
     * @return \DateTime
     */
    public function getDPEndDate()
    {
        return $this->dPEndDate;
    }

    /**
     * Sets a new dPEndDate
     *
     * Дата закрытия ПС
     *
     * @param \DateTime $dPEndDate
     * @return static
     */
    public function setDPEndDate(\DateTime $dPEndDate)
    {
        $this->dPEndDate = $dPEndDate;
        return $this;
    }

    /**
     * Gets as baseEndPS
     *
     * Код основания закрытия ПС:
     *  7.1.1 - перевод в другой банк, закрытие всех счетов в банке
     *  7.1.2 - исполнение сторонами всех обязательств по контракту (кредитному договору), включая исполнение обязательств третьим лицом - резидентом (другим лицом - резидентом)
     *  7.1.3 - уступка резидентом требования по контракту (кредитному договору) другому лицу - резиденту либо перевод долга резидентом по контракту (кредитному договору) на другое лицо - резидента
     *  7.1.4 - уступка резидентом требования по контракту (кредитному договору) нерезиденту
     *  7.1.5 - исполнение (прекращение) обязательств по контракту (кредитному договору) по иным основаниям
     *  7.1.6 - прекращение оснований, требующих оформления ПС, ошибочное,
     *  20.4 оформление ПС
     *  7.9 - по истечении 180 дней, следующих за датой завершения исполнения обязательств
     *
     * @return string
     */
    public function getBaseEndPS()
    {
        return $this->baseEndPS;
    }

    /**
     * Sets a new baseEndPS
     *
     * Код основания закрытия ПС:
     *  7.1.1 - перевод в другой банк, закрытие всех счетов в банке
     *  7.1.2 - исполнение сторонами всех обязательств по контракту (кредитному договору), включая исполнение обязательств третьим лицом - резидентом (другим лицом - резидентом)
     *  7.1.3 - уступка резидентом требования по контракту (кредитному договору) другому лицу - резиденту либо перевод долга резидентом по контракту (кредитному договору) на другое лицо - резидента
     *  7.1.4 - уступка резидентом требования по контракту (кредитному договору) нерезиденту
     *  7.1.5 - исполнение (прекращение) обязательств по контракту (кредитному договору) по иным основаниям
     *  7.1.6 - прекращение оснований, требующих оформления ПС, ошибочное,
     *  20.4 оформление ПС
     *  7.9 - по истечении 180 дней, следующих за датой завершения исполнения обязательств
     *
     * @param string $baseEndPS
     * @return static
     */
    public function setBaseEndPS($baseEndPS)
    {
        $this->baseEndPS = $baseEndPS;
        return $this;
    }

    /**
     * Gets as result
     *
     * Результат обработки ПС: 1 - закрыт, 0 - отказ в закрытии
     *
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets a new result
     *
     * Результат обработки ПС: 1 - закрыт, 0 - отказ в закрытии
     *
     * @param boolean $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Gets as formalCause
     *
     * Причина отказа
     *
     * @return \common\models\sbbolxml\response\CaseType
     */
    public function getFormalCause()
    {
        return $this->formalCause;
    }

    /**
     * Sets a new formalCause
     *
     * Причина отказа
     *
     * @param \common\models\sbbolxml\response\CaseType $formalCause
     * @return static
     */
    public function setFormalCause(\common\models\sbbolxml\response\CaseType $formalCause)
    {
        $this->formalCause = $formalCause;
        return $this;
    }


}

