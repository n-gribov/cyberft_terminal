<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\models\form\WizardForm;
use addons\swiftfin\models\SwiftfinTemplate;
use addons\swiftfin\SwiftfinModule;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\cyberxml\CyberXmlDocument;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * TemplatesController implements the CRUD actions for SwiftfinTemplate model.
 */
class TemplatesController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all SwiftfinTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            // Получить из БД список шаблонов через компонент авторизации доступа к терминалам
            'query' => Yii::$app->terminalAccess->query(SwiftfinTemplate::className())
        ]);

        // Вывести страницу
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'listType' => 'swiftTemplates',
            'model' => new SwiftfinTemplate()
        ]);
    }

    /**
     * Displays a single SwiftfinTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // Вывести страницу
        return $this->render('view', [
            // Получить из БД документ с указанным id
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SwiftfinTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SwiftfinTemplate(['terminalId' => Yii::$app->exchange->defaultTerminal->id]);

        if (Yii::$app->request->get('fromId')) {
            // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
            $importDocument = Yii::$app->terminalAccess->findModel(
                    Document::className(),
                    Yii::$app->request->get('fromId')
            );

            $model->sender = $importDocument->sender;
            $model->recipient = $importDocument->receiverParticipantId;
            $model->docType = str_replace('MT', '', $importDocument->type);
            $text = (string) CyberXmlDocument::getTypeModel($importDocument->actualStoredFileId);

            if (preg_match('#{3:{113:(.+?)}}#is', $text, $arr)) {
                $model->bankPriority = $arr[1];
            }

            if (preg_match('#{4:(.+?)-}#is', $text, $arr)) {
                $model->text = $arr[1];
            }
        }

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Зарегистрировать событие создания шаблона в модуле мониторинга
                Yii::$app->monitoring->extUserLog('CreateTemplateSwiftfin', ['swiftfinTemplateId' => $model->id]);

                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }

            // Поместить в сессию флаг сообщения об ошибке сохранения шаблона
            Yii::$app->session->setFlash('error', Yii::t('app/swiftfin', 'Could not save template'));
        }

        $model->sender = Yii::$app->exchange->defaultTerminal->terminalId;

        // Вывести страницу создания
        return $this->render('create', [
            'model' => $model,
            'docTypes' => $this->getSwiftTypes()
        ]);
        
    }

    /**
     * Updates an existing SwiftfinTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере и модель сохранена в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // Вывести страницу редактирования
            return $this->render('update', [
                'model' => $model,
                'docTypes' => $this->getSwiftTypes()
            ]);
        }
    }

    /**
     * Deletes an existing SwiftfinTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Найти документ в БД и удалить его из БД
        $this->findModel($id)->delete();

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionCreateSwiftfin($id)
    {
        // Получить из БД шаблон с указанным id через компонент авторизации доступа к терминалам
        $template = Yii::$app->terminalAccess->findModel(SwiftfinTemplate::className(), $id);

        // Кэширование данных шаблона

        $wizard = new WizardForm;
        $wizard->contentType = $template->docType;
        $wizard->sender = $template->sender;
        $wizard->recipient = $template->recipient;
        $wizard->terminalCode = $template->terminalCode;
        $wizard->bankPriority = $template->bankPriority;

        Yii::$app->cache->set('swiftfin/wizard/step1-' . Yii::$app->session->id, $wizard);
        Yii::$app->cache->set('swiftfin/template-text', $template->text);

        // Перенаправить на страницу визарда
        return $this->redirect(['/swiftfin/wizard/']);

    }

    /**
     * Метод ищет модель шаблона в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД шаблон с указанным id через компонент авторизации доступа к терминалам
        $model = Yii::$app->terminalAccess->findModel(SwiftfinTemplate::className(), $id);
 
        return $model;
    }

    protected function getSwiftTypes()
    {
        $wizard = new WizardForm;
        return $wizard->getSupportedTypes();
    }
}
