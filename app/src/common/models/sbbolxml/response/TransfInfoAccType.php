<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing TransfInfoAccType
 *
 *
 * XSD Type: TransfInfoAcc
 */
class TransfInfoAccType
{

    /**
     * Номер счета зачисления
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Номер в списке по порядку
     *
     * @property integer $numSt
     */
    private $numSt = null;

    /**
     * Результат зачисления зарплаты на счет.
     *  Возможные значения:
     *  Зачислено;
     *  НеЗачислено;
     *  Требуется подтверждение sms-паролем;
     *  На проверке у специалиста Банка;
     *  Отвергнут Банком;
     *  и д.р.
     *
     * @property string $result
     */
    private $result = null;

    /**
     * Дата зачисления зарплаты на счет/ Дата обработки записи.
     *  Дата зачисления зарплаты на счет в случае, если в результате передан статус «Зачислено»
     *  Дата обработки записи в случае, если в результате передан один из статусов ОшибкаФИО,
     *  СчетЗакрыт, СчетОтсутствует, НеЗачислено. По умолчанию – пустая. Опционально: может быть
     *  заполнена датой обработки квитка
     *
     * @property \DateTime $resDate
     */
    private $resDate = null;

    /**
     * Сообщение из Банка. Передавать расшифровку статуса Зачислено или НеЗачислено,
     *  соответствующую коду статуса
     *
     * @property string $annotation
     */
    private $annotation = null;

    /**
     * Сообщение Клиенту
     *
     * @property string $message
     */
    private $message = null;

    /**
     * Идентификатор смс
     *
     * @property string $smsId
     */
    private $smsId = null;

    /**
     * Gets as accNum
     *
     * Номер счета зачисления
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счета зачисления
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as numSt
     *
     * Номер в списке по порядку
     *
     * @return integer
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер в списке по порядку
     *
     * @param integer $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }

    /**
     * Gets as result
     *
     * Результат зачисления зарплаты на счет.
     *  Возможные значения:
     *  Зачислено;
     *  НеЗачислено;
     *  Требуется подтверждение sms-паролем;
     *  На проверке у специалиста Банка;
     *  Отвергнут Банком;
     *  и д.р.
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets a new result
     *
     * Результат зачисления зарплаты на счет.
     *  Возможные значения:
     *  Зачислено;
     *  НеЗачислено;
     *  Требуется подтверждение sms-паролем;
     *  На проверке у специалиста Банка;
     *  Отвергнут Банком;
     *  и д.р.
     *
     * @param string $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Gets as resDate
     *
     * Дата зачисления зарплаты на счет/ Дата обработки записи.
     *  Дата зачисления зарплаты на счет в случае, если в результате передан статус «Зачислено»
     *  Дата обработки записи в случае, если в результате передан один из статусов ОшибкаФИО,
     *  СчетЗакрыт, СчетОтсутствует, НеЗачислено. По умолчанию – пустая. Опционально: может быть
     *  заполнена датой обработки квитка
     *
     * @return \DateTime
     */
    public function getResDate()
    {
        return $this->resDate;
    }

    /**
     * Sets a new resDate
     *
     * Дата зачисления зарплаты на счет/ Дата обработки записи.
     *  Дата зачисления зарплаты на счет в случае, если в результате передан статус «Зачислено»
     *  Дата обработки записи в случае, если в результате передан один из статусов ОшибкаФИО,
     *  СчетЗакрыт, СчетОтсутствует, НеЗачислено. По умолчанию – пустая. Опционально: может быть
     *  заполнена датой обработки квитка
     *
     * @param \DateTime $resDate
     * @return static
     */
    public function setResDate(\DateTime $resDate)
    {
        $this->resDate = $resDate;
        return $this;
    }

    /**
     * Gets as annotation
     *
     * Сообщение из Банка. Передавать расшифровку статуса Зачислено или НеЗачислено,
     *  соответствующую коду статуса
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Sets a new annotation
     *
     * Сообщение из Банка. Передавать расшифровку статуса Зачислено или НеЗачислено,
     *  соответствующую коду статуса
     *
     * @param string $annotation
     * @return static
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
        return $this;
    }

    /**
     * Gets as message
     *
     * Сообщение Клиенту
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets a new message
     *
     * Сообщение Клиенту
     *
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Gets as smsId
     *
     * Идентификатор смс
     *
     * @return string
     */
    public function getSmsId()
    {
        return $this->smsId;
    }

    /**
     * Sets a new smsId
     *
     * Идентификатор смс
     *
     * @param string $smsId
     * @return static
     */
    public function setSmsId($smsId)
    {
        $this->smsId = $smsId;
        return $this;
    }


}

