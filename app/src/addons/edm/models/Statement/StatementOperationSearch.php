<?php

namespace addons\edm\models\Statement;

use common\helpers\DateHelper;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class StatementOperationSearch extends Model
{
    public $ValueDate;
    public $Number;
    public $PayerAccountNum;
    public $PayerBIK;
    public $PayeeName;
    public $PayeeBIK;
    public $Debit;
    public $Credit;
    public $Purpose;


    public function rules()
    {
        return [
            [['ValueDate', 'Number', 'PayerAccountNum',
                'PayerBIK', 'PayerBIK', 'PayeeName',
                'PayeeBIK', 'Debit', 'Credit', 'Purpose'], 'safe']
        ];
    }

    public function search($params, $dataProvider)
    {
        $className = $this->getClassName();

        // Если фильтры не указаны или в DataProvider пустые данные,
        // возвращаем данные в исходном виде
        if (!$params || !isset($params[$className]) || !$dataProvider->allModels) {
            return $dataProvider;
        }

        // Получаем параметры из запроса,
        // по которым требуется фильтровать данные
        $query = $this->getParams($params);

        // Если в фильтрах пришли пустые поля запросов,
        // то возвращаем данные в исходном виде
        if (!$query) {
            return $dataProvider;
        }

        // Получаем массив с данными из DataProvider
        $data = $dataProvider->allModels;

        // Формируем новый массив моделей для записи в новый DataProvider
        $newAllModels = [];

        $fieldsAddValues = ['PayerAccountNum', 'PayerBIK', 'PayeeName', 'PayeeBIK'];
        $fieldsFloat = ['Debit', 'Credit'];
        $fieldsDate = ['ValueDate'];

        // Перебор пришедших данных и использование фильтров
        foreach($data as $item) {
            // Массив-флаг
            $isFilter = [];

            // Проходимся по доступным фильтрам и проверяем их значения со строкой данных

            foreach($query as $id => $filter) {
                // Проверка типа 'like'
                if ($filter['rule'] == 'like') {
                    // Для некоторых полей требуется более расширенное значение для поиска значений
                    if (in_array($id, $fieldsAddValues)) {
                        $searchData = $this->getPayerSearchValue($id, $item);
                    } else {
                        $searchData = $item[$id];
                    }

                    $isFilter[] = boolval(mb_stristr($searchData, $filter['value']));
                } elseif ($filter['rule'] == 'eq') {

                    if (in_array($id, $fieldsFloat)) {
                        $compareValue = $this->getFloatValue($item[$id]);
                    } else if (in_array($id, $fieldsDate)) {
                        $compareValue = $this->getDateValue($item[$id]);
                    } else {
                        $compareValue = $item[$id];
                    }

                    // Проверка типа "равенство"
                    $isFilter[] = $compareValue == $filter['value'];
                }
            }

            // Если в массиве есть хотя бы одно совпадение,
            // то строка в него не входит
            if (!in_array(false, $isFilter)) {
                $newAllModels[] = $item;
            }
        }

        // Формируем новый ArrayDataProvider
        $newDataProvider = new ArrayDataProvider([
            'allModels' => $newAllModels,
            'pagination' => false
        ]);

        $this->setAttributesValues($params);

        return $newDataProvider;
    }

    /**
     * Формирование наименования с дополнительными значениями
     * для более точного поиска
     * @param $item
     * @return string
     */
    private function getPayerSearchValue($field, $item)
    {
        switch($field) {
            case 'PayerAccountNum':
                $value = $item['PayerAccountNum'] . $item['PayerName'] . $item['PayerINN'] . $item['PayerKPP'];
                break;
            case 'PayerBIK':
                $value = $item['PayerBIK'] . $item['PayerBankName'] . $item['PayerBankAccountNum'];
                break;
            case 'PayeeName':
                $value = $item['PayeeAccountNum'] . $item['PayeeName'] . $item['PayeeINN'] . $item['PayeeKPP'];
                break;
            case 'PayeeBIK':
                $value = $item['PayeeBIK'] . $item['PayeeBankName'] . $item['PayeeBankAccountNum'];
                break;
            default:
                $value = $item[$field];
        }

        return $value;
    }

    /**
     * Преобразование значения в float-вид
     * @param $value
     * @return string
     */
    private function getFloatValue($value)
    {
        return \Yii::$app->formatter->asDecimal($value, 2);
    }

    /**
     * Преобразование даты в нужный вид
     * @param $value
     * @return bool|string
     */
    private function getDateValue($value)
    {
        return DateHelper::formatDate($value);
    }

    /**
     * Функция формирования массива
     * фильтров из пришедших параметров
     * @param $params
     * @return array
     */
    private function getParams($params)
    {
        $className = $this->getClassName();

        // Получаем массив с фильтрами
        $query = $params[$className];

        // Массив для возврата фильтров и их значений
        $arrParams = [];

        $likeRuleFields = ['Purpose', 'PayerAccountNum', 'PayerBIK', 'PayeeName', 'PayeeBIK'];

        // Перебираем массив свойств и записываем не пустые значения
        foreach($query as $id => $item) {
            if ($item == '0' || !empty($item)) {

                if (in_array($id, $likeRuleFields)) {
                    $arrParams[$id] = [
                        'value' => $item,
                        'rule' => 'like'
                    ];
                } else {
                    $arrParams[$id] = [
                        'value' => $item,
                        'rule' => 'eq'
                    ];
                }
            }
        }

        return $arrParams;
    }

    private function getClassName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    private function setAttributesValues($queryParams)
    {
        $className = $this->getClassName();

        // Получаем массив с фильтрами
        $query = $queryParams[$className];

        foreach($query as $attr => $value) {
            $this->$attr = $value;
        }
    }

}