<?php
namespace addons\edm\validators;

use common\helpers\StringHelper;
use yii\base\Exception;
use \yii\validators\Validator;

class CbrKeyingValidator extends Validator {

    /**
     * Ключ по которому можно получить значение из модели
     * @var string
     */
    public $bikKey;
    public $skipZero20 = false;
    /**
     * @var string
     */
    protected $bik;

    public function validateAttribute($model, $attribute)
    {
        // костыль, не получается из модели передать значение как опцию валидатора
        if (isset($model->{$this->bikKey})) {
            $this->bik = $model->{$this->bikKey};
        } else {
            throw new Exception('Undefined key bankBik ' . $this->bikKey);
        }

        parent::validateAttribute($model, $attribute);
    }

    /**
     * @inheritdoc
     */
    public function validateValue($checkingAccount)
    {
        if ($this->skipZero20) {
            return null;
        }

        if (!$this->bik) {
            return [
                'Значение БИК не задано', []
            ];
        }

        $isTreasuryAccount = StringHelper::startsWith($checkingAccount, '03');
        if ($isTreasuryAccount) {
            return null;
        }

        $currentKey = $checkingAccount[8];
        $rkcNum = '0' . substr($this->bik, 4, 2); // РКЦ
        $orgNum = substr($this->bik, 6, 3); // номер кредитной организации

        /**
         * т.к. нет четкого признака по какому номеру считать ключ, пробуем по РКЦ и номеру кредитной организации
         */
        if (
            $currentKey != ($key = $this->getKey($checkingAccount, $orgNum))
            && $currentKey != ($key = $this->getKey($checkingAccount, $rkcNum))
        ) {
            return [
                'Ключ счета {value} не удовлетворяет алгоритму расчета контрольного ключа "{currentKey}" и не совпадает с рассчитанным ключом "{key}"',
                ['key' => $key, 'currentKey' => $currentKey]
            ];
        } else if (
            !$this->checkKey($key, $checkingAccount, $orgNum)
            && !$this->checkKey($key, $checkingAccount, $rkcNum)
        ) {
            return [
                'Указанный номер счета {value} не удовлетворяет алгоритму расчета контрольного ключа ({key}) относительно указанного БИКа',
                ['key' => $key]
            ];
        }

        return null;
    }

    /**
     * @param $account
     * @param $orgNum
     * @return int
     */
    protected function getKey($account, $orgNum)
    {
        $account[8] = '0';
        return $this->calculateKey($account, $orgNum);
    }

    /**
     * Проверка расчитанного ключ
     * @param $key
     * @param $account
     * @param $orgNum
     * @return bool
     */
    protected function checkKey($key, $account, $orgNum)
    {
        $account[8] = $key;
        return $this->calculateKey($account, $orgNum) == 0;
    }

    /**
     * Вычисление контрольного ключа
     * @param $account
     * @param $orgNum
     * @return int
     */
    protected function calculateKey($account, $orgNum)
    {
        $account = $orgNum . $account;
        $weight  = [7, 1, 3];
        $key     = 0;
        $c       = strlen($account);
        
        for ($i = 0; $i < $c; $i++) {
            $key += ($account[$i] * $weight[$i % 3] % 10);
        }

        return ($key % 10 * 3) % 10;
    }
}