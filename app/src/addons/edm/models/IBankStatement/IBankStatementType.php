<?php

namespace addons\edm\models\IBankStatement;

use common\base\BaseType;
use common\helpers\StringHelper;
use Yii;
use yii\helpers\ArrayHelper;

class IBankStatementType extends BaseType
{
    public $account;
    public $beginDate;
    public $endDate;
    public $inRest;
    public $outRest;
    public $credit;
    public $debit;
    public $lastOperationDay;
    public $transactions = [];

    private $_cachedAttributesTags;
    private $_tagAttributes;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function loadFromString($text, $isFile = false, $encoding = null)
    {
        $rows = preg_split('/[\\r\\n]+/', StringHelper::utf8($text, $encoding));

        $transaction = [];
        $isOperation = false;

        foreach ($rows as $row) {
            // Тэги, которые надо пропустить
            if (stristr($row, '$OPERS_LIST') !== false ||
                stristr($row, '$OPERS_LIST_END') !== false) {
                continue;
            }

            // Начало платежки
            if (trim($row) == '$OPERATION') {
                $isOperation = true;
                continue;
            }

            // Конец платежки
            if (trim($row) == '$OPERATION_END') {
                $isOperation = false;
                $this->transactions[] = $transaction;
                $transaction = [];
                continue;
            }

            if ($isOperation) {
                // Добавляем данные о текущей транзакции
                $tagAndValuePair = explode('=', trim($row), 2);
                if (count($tagAndValuePair) === 1) {
                    continue;
                } else {
                    list($tag, $value) = $tagAndValuePair;
                }

                if (($attribute = $this->getAttributeByTag($tag, true))) {
                    $transaction[$attribute] = $value;
                } else {
                    // если тэг не найден, пропускаем его
                    Yii::info("Unsupported tag: $row");
                    continue;
                }
            } else {
                // Добавляем данные в шапку документа
                $tagAndValuePair = explode('=', trim($row), 2);
                if (count($tagAndValuePair) === 1) {
                    continue;
                } else {
                    list($tag, $value) = $tagAndValuePair;
                }

                if (($attribute = $this->getAttributeByTag($tag, true))) {
                    $this->$attribute = $value;
                } else {
                    // если тэг не найден, пропускаем его
                    Yii::info("Unsupported tag: $row");
                    continue;
                }
            }
        }

        return $this;
    }

    public function headerAttributeTags()
    {
        return [
            'account' => 'ACCOUNT',
            'beginDate' => 'BEGIN_DATE',
            'endDate' => 'END_DATE',
            'inRest' => 'IN_REST',
            'outRest' => 'OUT_REST',
            'credit' => 'CREDIT',
            'debit' => 'DEBET',
            'lastOperationDay' => 'LAST_OPER_DAY'
        ];
    }

    public function operationAttributeTags()
    {
        return [
            'operationId' => 'OPER_ID',
            'operationDate' => 'OPER_DATE',
            'operationCode' => 'OPER_CODE',
            'documentNumber' => 'DOC_NUM',
            'documentDate' => 'DOC_DATE',
            'participant1Inn' => 'CORR_INN',
            'participant1Name' => 'CORR_NAME',
            'participant1Account' => 'CORR_ACCOUNT',
            'participant1BankBic' => 'CORR_BANK_BIC',
            'participant1BankCorrespondentAccount' => 'CORR_BANK_ACC',
            'participant1BankName' => 'CORR_BANK_NAME',
            'participant1Kpp' => 'CORR_KPP',
            'participant2Inn' => 'CLN_INN',
            'participant2Name' => 'CLN_NAME',
            'participant2Account' => 'CLN_ACCOUNT',
            'participant2BankBic' => 'CLN_BANK_BIC',
            'participant2BankCorrespondentAccount' => 'CLN_BANK_ACC',
            'participant2BankName' => 'CLN_BANK_NAME',
            'participant2Kpp' => 'CLN_KPP',
            'sum' => 'AMOUNT',
            'rubSum' => 'RUR_AMOUNT',
            'code' => 'CODE',
            'operationDetails' => 'OPER_DETAILS',
            'chargeCreator' => 'CHARGE_CREATOR',
            'chargeKbk' => 'CHARGE_KBK',
            'chargeOkato' => 'CHARGE_OKATO',
            'chargeBasis' => 'CHARGE_BASIS',
            'chargePeriod' => 'CHARGE_PERIOD',
            'chargeDocNumber' => 'CHARGE_NUM_DOC',
            'chargeDocDate' => 'CHARGE_DATE_DOC',
            'chargeType' => 'CHARGE_TYPE',
            'queue' => 'QUEUE',
            'incomeBankDate' => 'INCOME_BANK_DATE',
            'creditDate' => 'CREDIT_DATE',
            'valueDate' => 'VALUE_DATE',
            'keyId' => 'KEY_ID',
            'signTs' => 'SIGN_TS',
            'sign' => 'SIGN',
        ];
    }

    protected function getAttributeByTag($tag, $notSensitiveCase = false)
    {
        $map = $this->getTagAttributes();

        // если не нужно учитывать регистр символов тэгов
        if ($notSensitiveCase) {
            $tag = mb_strtolower($tag);

            if (!$this->_cachedAttributesTags) {
                $keys = array_keys($map);
                $values = array_values($map);

                $keys = array_map(function($item) {
                    return mb_strtolower($item);
                },$keys);

                $map = array_combine($keys, $values);
                $this->_cachedAttributesTags = $map;
            } else {
                $map = $this->_cachedAttributesTags;
            }
        }

        return isset($map[$tag])?$map[$tag]:null;
    }

    protected function getTagAttributes()
    {
        if (!empty($this->_tagAttributes)) {
            return $this->_tagAttributes;
        }

        return $this->_tagAttributes = array_merge(
            array_flip($this->headerAttributeTags()),
            array_flip($this->operationAttributeTags())
        );
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }
}