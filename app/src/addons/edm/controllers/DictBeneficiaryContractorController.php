<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\DocumentPermission;
use Yii;
use yii\filters\AccessControl;
use common\base\BaseServiceController;
use addons\edm\models\DictBeneficiaryContractor;
use addons\edm\models\DictBeneficiaryContractorSearch;
use yii\web\NotFoundHttpException;
use addons\edm\models\DictCurrency;
use yii\web\Response;

/**
 * Контроллер для работы со счетами плательщиков
 * @package addons\edm\controllers
 */
class DictBeneficiaryContractorController extends BaseServiceController
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'get-currency', 'list', 'get-type-by-number'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update'],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Журнал всех счетов
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DictBeneficiaryContractorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listType' => 'dictBeneficiaryContractor'
        ]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->request->get('emptyLayout')) {
            throw new \yii\web\HttpException('403');
        }

        $model = new DictBeneficiaryContractor();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'emptyLayout' => 1]);
            }
        }

        return $this->render('_form', ['model' => $model]);
    }

    /**
     * Редактирование существующего счета плательщика
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->get('emptyLayout')) {
                    return $this->redirect(['view', 'id' => $model->id, 'emptyLayout' => 1]);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Просмотр счета плательщика
     */
    public function actionView($id)
    {
        if (Yii::$app->request->get('emptyLayout')) {
            return $this->render('_view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Удаление организации
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', Yii::t('edm', 'The payer account was successfully deleted'));
        return $this->redirect(['index']);
    }

    /**
     * Получение валюты по коду
     * @param $code
     */
    public function actionGetCurrency($code)
    {
        if (Yii::$app->request->isGet && Yii::$app->request->isAjax) {
            $currency = DictCurrency::findOne(['code' => $code]);

            // Возвращаем id валюты,
            // если она найдена
            if ($currency) {
                return $currency->id;
            }

        }
    }

    /**
     * Поиск модели справочника по id
     */
    protected function findModel($id)
    {
        if (($model = DictBeneficiaryContractor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Получение списка контрагентов получателей
     * @param null $q
     * @param null $role
     * @param null $id
     * @return array
     */
    public function actionList($q = null, $role = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (is_null($id)) {

            $query = Yii::$app->terminalAccess->query(DictBeneficiaryContractor::className());

            if (is_numeric($q)) {
                // Поиск по номеру счета
                $query->andfilterWhere(['like', 'account', $q]);

                // Поиск по ИНН
                $query->orFilterWhere(['like', 'inn', $q]);
            } else {
                $query->andfilterWhere(['like', 'name', $q]);
            }

            if ($role) {
                $query->andWhere(['role' => [$role, '']]);
            }

            $query->limit(20);

            $items = $query->all();

            $out = ['results' => []];
            foreach ($items as $i => $item) {
                $out['results'][$i] = array_merge(
                    $item->getAttributes(), [
                        'bank' => $item->bank->getAttributes(),
                        'objectId' => $item->id
                    ]
                );
                /**
                 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
                 */
                $out['results'][$i]['id'] = $out['results'][$i]['account'];
            }
        } else if ($id > 0) {
            $out['results'] = [['id' => $id, 'text' => DictContractor::find($id)->name]];
        }

        return $out;
    }

    public function actionGetTypeByNumber($number)
    {
        return EdmHelper::checkParticipantTypeByAccount($number);
    }

    /**
     * Метод предоставляет пустой (без шапки, сайдбара и футера), когда это необходимо
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->get('emptyLayout')) {
            $this->layout = '@backend/views/layouts/empty';
        }

        return parent::beforeAction($action);
    }

}
