<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictContractor;
use addons\edm\models\DictContractorSearch;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class DictContractorController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list','view'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ]
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->request->get('emptyLayout')) {
            $this->layout = '@backend/views/layouts/empty';
        }

        return parent::beforeAction($action);
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $searchModel = new DictContractorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        // Получить из БД запись плательщика с указанным id
        $model = $this->findModel($id);

        if (Yii::$app->request->get('emptyLayout')) {
            // Вывести страницу
            return $this->render('_view', [
                'model' => $model
            ]);
        }
        // Вывести страницу просмотра
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new DictContractor();

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            $model->terminalId = Yii::$app->exchange->defaultTerminal->id;
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Зарегистрировать событие добавления нового контрагента в модуле мониторинга
                Yii::$app->monitoring->extUserLog('AddNewEdmContractor', ['id' => $model->id]);

                if (Yii::$app->request->getIsPjax()) {
                    return $this->renderPartial('_view', ['model' => $model]);
                } else if (Yii::$app->request->get('emptyLayout')) {
                    // Перенаправить на страницу просмотра с пустым оформлением
                    return $this->redirect(['view', 'id' => $model->id, 'emptyLayout' => 1]);
                } else {
                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if (Yii::$app->request->getIsPjax()) {
            // Вывести форму
            return $this->renderPartial('_form', compact('model'));
        } else if (Yii::$app->request->get('emptyLayout')) {
            // Вывести форму
            return $this->render('_form', compact('model'));
        }
        
        // Вывести страницу создания
        return $this->render('create', compact('model'));
    }

    public function actionUpdate($id)
    {
        // Получить из БД запись плательщика с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            $model->terminalId = Yii::$app->exchange->defaultTerminal->id;
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Вывести страницу редактирования
        return $this->render('update', compact('model'));
    }

    public function actionDelete($id)
    {
        // Получить из БД запись плательщика с указанным id и удалить её
        $this->findModel($id)->delete();

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionList($q = null, $role = null, $id = null)
    {
        // Включить формат вывода JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (is_null($id)) {
            $query = Yii::$app->terminalAccess->query(DictContractor::className())->limit(20);
            if (is_numeric($q)) {
                $query->filterWhere(['like', 'account', $q]);
            } else {
                $query->filterWhere(['like', 'name', $q]);
            }

            if ($role) {
                $query->andWhere(['role' => [$role, '']]);
            }

            /** @var DictContractor[] $items */
            $items = $query->all();

            $out = ['results' => []];
            foreach ($items as $i => $item) {
                $out['results'][$i] = array_merge(
                    $item->getAttributes(), [
                        'bank' => $item->bank->getAttributes()
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

    /*
     * Метод ищет модель в БД по первичному ключу
     */
    protected function findModel($id)
    {
        // Получить из БД запись плательщика с указанным id
        // через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(DictContractor::className(), $id);
    }
}
