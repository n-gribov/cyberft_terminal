<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\DictBankHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictBankSearch;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use common\modules\certManager\models\Cert;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;

class DictBankController extends BaseServiceController
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'view'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                            'allow' => true,
                            'roles' => ['admin'],
                    ],
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

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $searchModel = new DictBankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        // Вывести страницу
        return $this->render('view', [
            // Получить из БД документ с указанным id
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSyncFile()
    {
        if (!Yii::$app->request->getIsPost()) {
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        $errors = [];
        $log    = [];
        $file   = UploadedFile::getInstanceByName('file');

        if (!$file) {
            $errors['file'] = Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'File')]);
        } else if (!in_array($file->type, ['application/x-zip-compressed', 'application/zip', 'application/octet-stream'])) {
            $errors['file'] = Yii::t('app/error', 'Invalid source file format');
        } else {
            try {
                $log = DictBankHelper::syncFile($file->tempName);

                // Зарегистрировать событие загрузки справочника банков в модуле мониторинга
                Yii::$app->monitoring->extUserLog('UploadEdmBanks');
            } catch (\Exception $e) {
                $errors['file'] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $searchModel = new DictBankSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            // Вывести страницу
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'errors' => $errors,
            ]);
        } else {
            // Вывести страницу
            return $this->render('sync', ['log' => $log]);
        }
    }

    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id
        $model = $this->findModel($id);
        $certs = Cert::find()->with('participant')->where(['role' => Cert::ROLE_SIGNER_BOT])->all();

        // Если данные модели успешно загружены из формы в браузере и модель сохранена в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        } else {
            // Вывести страницу редактирования
            return $this->render('update', compact('model', 'certs'));
        }
    }

    public function actionList($q = null, $id = null)
    {
        // Включить формат вывода JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => [['id' => '', 'text' => '']]];
        if (!is_null($q)) {
            $query = DictBank::find()
                //->from(DictBank::tableName())
                ->where(['like', 'bik', $q])
                ->orWhere(['like', 'name', $q])
                ->limit(20);

            /** @var DictBank[] $items */
            $items = $query->all();

            $out = ['results' => []];
            foreach ($items as $i => $item) {
                $out['results'][$i] = $item->getAttributes();
                /**
                 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
                 */
                $out['results'][$i]['id'] = $out['results'][$i]['bik'];
            }
        } else if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => DictBank::find($id)->name];
        }

        return $out;
    }

    /*
     * Метод ищет модель в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД банк с указанным БИК
        $model = DictBank::findOne(['bik' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
