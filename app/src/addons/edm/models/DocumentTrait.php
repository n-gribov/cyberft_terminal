<?php
namespace addons\edm\models;

use Yii;

trait DocumentTrait {

    public $number;
    public $sender;
    public $terminalCode;

    protected $_date;

    public function getType()
    {
        return static::TYPE;
    }

    public function getLabel()
    {
        return Yii::t('edm', static::LABEL);
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        if (!$this->_date) {
            $this->setDate(date('d.m.Y'));
        }
        return $this->_date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * Получение текущего счетчика документа заданного типа
     * @return mixed
     */
    public function getCounter()
    {
        return Yii::$app->redis->get($this->getCounterKey());
    }

    /**
     * Приращение текущего счетчика документа заданного типа
     * @return $this
     */
    public function counterIncrement()
    {
        $val = $this->getCounter();
        $val = ($val < 1000 ? 1000 : ($val + 1));
        $this->setCounter($val);
        return $this;
    }

    /**
     * Задать значение текущего счетчика документа заданного типа
     * @param $value
     */
    public function setCounter($value)
    {
        Yii::$app->redis->set($this->getCounterKey(), $value);
    }

    /**
     * Ключ текущего счетчика документа заданного типа
     * @return string
     */
    protected function getCounterKey()
    {
        return static::TYPE . 'Counter' . date('Y') . $this->getType();

        /**
         * @todo Надо сделать так:
         */
        /*
        return \common\helpers\RedisHelper::getKeyName(
            static::TYPE
            . 'Counter:'
            . date('Y')
            . $this->getType()
        );
        */
    }
}