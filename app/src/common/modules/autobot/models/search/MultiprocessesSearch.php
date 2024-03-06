<?php

namespace common\modules\autobot\models\search;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Terminal;
use Yii;

class MultiprocessesSearch extends Model
{
    public $terminalId;
    public $organization;
    public $status;

    public function rules()
    {
        return [
            [['terminalId', 'organization', 'status'], 'safe']
        ];
    }

    public function search($params, $dataProvider)
    {
        // Если фильтры не указаны или в DataProvider пустые данные,
        // возвращаем данные в исходном виде
        if (!$params || !isset($params['MultiprocessesSearch']) || !$dataProvider->allModels) {
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

        // Формируем новый массив моделей
        // для записи в новый DataProvider
        $newAllModels = [];

        // Перебор пришедших данных и использование фильтров
        foreach($data as $item) {

            // Массив-флаг
            $isFilter = [];

            // Проходимся по доступным фильтрам и
            // проверяем их значения со строкой данных

            foreach($query as $id => $filter) {

                // Проверка типа 'like'
                if ($filter['rule'] == 'like') {
                    $isFilter[] = boolval(stristr($item[$id], $filter['value']));
                } elseif ($filter['rule'] == 'eq') {
                    // Проверка типа "равенство"
                    $isFilter[] = $item[$id] == boolval($filter['value']);
                }
            }

            // Если в массиве есть хотя бы одно не совпадение,
            // то строка в него не входит
            if (!in_array(false, $isFilter)) {
                $newAllModels[] = $item;
            }
        }

        // Сортировка по имени терминала
        uasort($newAllModels, function($a, $b) {
            if ($a['terminalId'] == $b['terminalId']) {
                return 0;
            }
            return $a['terminalId'] > $b['terminalId'];
        });

        // Формируем новый ArrayDataProvider
        $newDataProvider = new ArrayDataProvider([
            'allModels' => $newAllModels,
            'pagination' => false
        ]);

        return $newDataProvider;
    }

    /**
     * Функция формирования массива
     * фильтров из пришедших параметров
     * @param $params
     * @return array
     */
    private function getParams($params)
    {
        // Получаем массив с фильтрами
        $query = $params['MultiprocessesSearch'];

        // Массив для возврата фильтров и их значений
        $arrParams = [];

        // Перебираем массив свойств и записываем не пустые значения
        foreach($query as $id => $item) {
            if ($item == '0' || !empty($item)) {

                // Формируем массив с фильтрами и
                // правилами проверки их значений
                if ($id == 'terminalId' ||
                    $id == 'organization') {
                    $arrParams[$id] = [
                        'value' => $item,
                        'rule' => 'like'
                    ];
                } elseif ($id == 'status' ||
                           $id == 'hasActiveControllerKeys' ||
                           $id == 'exchangeStatus') {
                    $arrParams[$id] = [
                        'value' => $item,
                        'rule' => 'eq'
                    ];
                }
            }
        }

        return $arrParams;
    }

    /**
     * Метод формирует список вариантов для фильтра по статусу
     * @return array
     */
    public function getStatusLabels()
    {
        return [
            true => Yii::t('app/terminal', 'Active'),
            false => Yii::t('app/terminal', 'Inactive'),
        ];
    }

    /**
     * Метод формирует список вариантов для
     * фильтра по наличию ключа контролера
     * @return array
     */
    public function getControllerKeyLabels()
    {
        return [
            true => Yii::t('app/autobot', 'Added'),
            false => Yii::t('app/autobot', 'Not present'),
        ];
    }

    public function getExchangeStatusLabels()
    {
        return [
            true => Yii::t('app/autobot', 'Running'),
            false => Yii::t('app/autobot', 'Stopped'),
        ];
    }
}