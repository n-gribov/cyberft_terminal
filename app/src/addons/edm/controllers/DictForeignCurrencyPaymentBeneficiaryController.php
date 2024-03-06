<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use yii\filters\AccessControl;
use Yii;
use yii\web\Response;
use addons\edm\models\DictForeignCurrencyPaymentBeneficiary;

class DictForeignCurrencyPaymentBeneficiaryController extends BaseServiceController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Список получателей для ajax-запроса
     */
    public function actionList($q = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];

        // Получаем список получателей валютных платежей по терминалу
        $query = Yii::$app->terminalAccess->query(DictForeignCurrencyPaymentBeneficiary::className());

        $query->limit(20);

        if (is_numeric($q)) {
            // Поиск по номеру счета
            $query->andfilterWhere(['like', 'account', $q]);
        } else {
            // Поиск по наименованию
            $query->andfilterWhere(['like', 'description', $q]);
        }

        $items = $query->all();

        $out = ['results' => []];
        foreach ($items as $i => $item) {
            $out['results'][$i] = array_merge(
                $item->getAttributes()
            );
            $out['results'][$i]['id'] = $out['results'][$i]['account'];

            $description = $out['results'][$i]['description'];
            $name = preg_split('/([\r\n]+|,)/', $description)[0];
            $out['results'][$i]['name'] =  $name;
        }

        return $out;
    }
}