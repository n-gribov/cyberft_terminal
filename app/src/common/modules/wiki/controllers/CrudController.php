<?php

namespace common\modules\wiki\controllers;

use common\base\Controller as BaseController;
use common\helpers\FileHelper;
use common\modules\wiki\models\Attachment;
use common\modules\wiki\models\Dump;
use common\modules\wiki\models\Page;
use common\modules\wiki\models\PageSearch;
use common\modules\wiki\WikiModule;
use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class CrudController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['pageManage'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload-image' => ['post'],
                ],
            ]
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $searchModel  = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($parentId = 0)
    {
        $model = new Page(['scenario' => 'create', 'pid' => $parentId]);

        $parent = null;
        if (!empty($parentId)) {
            $parent = Page::findOne($parentId);
        }

        // Если данные модели успешно загружены из формы в браузере и модель сохарнилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу редактирвоания
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            // Вывести страницу
            return $this->render('create', [
                'model' => $model,
                'parent' => $parent
            ]);
        }
    }

    public function actionAttach($pageId = 0, $type = Attachment::TYPE_FILE)
    {
        $page = Page::findOne($pageId);

        if (empty($page)) {
            $result = [
                'error' => WikiModule::t('default', 'Files can be attached to created pages only'),
            ];
        } else {
            $file  = UploadedFile::getInstanceByName('file');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image', [])->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                $attach = Attachment::loadFromFile($pageId, $file, $type);

                if (!$attach->save()) {
                    $result = [
                        'error' => $model->getFirstError('file'),
                    ];
                } else {
                    $result = [
                        'filelink' => Url::toRoute([
                            'default/attachment-download',
                            'id' => $attach->id,
                            'inline' => true
                        ])
                    ];
                }
            }
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $result;
    }

    public function actionImagesList($pageId)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = Page::findOne($pageId);
        if (empty($page)) {
            throw new NotFoundHttpException();
        }

        $result = [];
        if (!empty($page->attachments)) {
            foreach ($page->attachments as $attach) {
                $url = Url::toRoute([
                    'default/attachment-download',
                    'id' => $attach->id,
                    'inline' => true
                ]);
                $result[] = [
                    'title' => $attach->title,
                    'thumb' => $url,
                    'image' => $url
                ];
            }
        }

        return $result;
    }

    public function actionUpdate($id)
    {
        // Получить из БД страницу с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Поместить в сессию флаг сообщения об успешном сохранении страницы
            Yii::$app->session->addFlash('success', WikiModule::t('default', 'Page updated'));
            // Перенаправить на страницу по умолчанию
            return $this->redirect('');
        } else {
            // Вывести страницу редактирования
            return $this->render('update', compact('model'));
        }
    }

    public function actionDelete($id)
    {
        // Получить из БД страницу с указанным id
        $model = $this->findModel($id);
        $deleteList = $this->getRecursiveDeleteList($model);
        $attachmentList = Attachment::findAll(['page_id' => $deleteList]);

        foreach($attachmentList as $attachment) {
            // Удалить вложение из БД
            $attachment->delete();
        }

        Page::deleteAll(['id' => $deleteList]);

        // Перенаправить на страницу вики
        return $this->redirect(['/wiki']);
    }

    private function getRecursiveDeleteList(Page $model)
    {
        $deleteList = [$model->id];
        $children = $model->getChildren()->all();

        foreach($children as $child) {
            $deleteList = array_merge($deleteList, $this->getRecursiveDeleteList($child));
        }

        return $deleteList;
    }

    public function actionAttachmentUpdate($id)
    {
        $model = Attachment::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException();
        }

        // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Поместить в сессию флаг сообщения об успешном сохранении вложения
            Yii::$app->session->addFlash('success', WikiModule::t('default', 'Attachment updated'));
            // Перенаправить на страницу по умолчанию
            return $this->redirect('');
        } else {
            // Вывести страницу
            return $this->render('attachment/update', ['model' => $model]);
        }
    }

    public function actionAttachmentDelete($id)
    {
        $model = Attachment::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException();
        }

        $pageId = $model->page->id;

        // Удалить вложение из БД
        if (!$model->delete()) {
            // Поместить в сессию флаг сообщения об ошибке удаления вложения
            Yii::$app->session->addFlash('error', WikiModule::t('default', 'Cannot delete attachment'));
            // Перенаправить на страницу по умолчанию
            return $this->redirect('');
        }

        // Поместить в сессию флаг сообщения об успешном удалении вложения
        Yii::$app->session->addFlash('success', WikiModule::t('default', 'Attachment deleted'));
        // Перенаправить на страницу редактирования
        return $this->redirect(['crud/update', 'id' => $pageId]);
    }

    public function actionExport()
    {
        if (!$model = Dump::loadFromCache()) {
            $model           = Dump::create();
            $model->jobToken = Yii::$app->resque->enqueue('common\modules\wiki\jobs\ExportJob', [], true);
            $model->status   = Dump::STATUS_WAITING;

            if (!$model->validate()) {

                throw new ServerErrorHttpException(WikiModule::t('default', 'Error while setting export job'));
            }

            // Сохранить модель в БД
            $model->save();
        }

        if (!$model->isReady()) {
            // Вывести страницу ожидания экспорта
            return $this->render('export-wait');
        } else {
            return \Yii::$app->response->sendStreamAsFile(
                $model->getTargetStream(), $model->getTargetFilename(),
                ['mimeType' => $model->getTargetMimeType()]
            );
        }
    }

    public function actionImport()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('file');
            if (empty($file)) {
                throw new \yii\web\BadRequestHttpException();
            }

            $result = FileHelper::storeTempFile($file->tempName, $file->name);

            if (false === $result) {
                // Поместить в сессию флаг сообщения об ошибке сохранения временного файла
                Yii::$app->session->addFlash('error', WikiModule::t('default', 'Cannot store temp file'));
            } else {
                Yii::$app->resque->enqueue('common\modules\wiki\jobs\ImportJob', [
                    'tempFile'  => $result,
                ]);
                // Поместить в сессию флаг сообщения об успешной загрузке архива
                Yii::$app->session->addFlash('success', WikiModule::t('default', 'Archive uploaded, work in progress'));
            }

            // Перенаправить на страницу по умолчанию
            return $this->redirect('');
        }
        // Вывести страницу импорта
        return $this->render('import', []);
    }

    /**
     * Метод ищет модель страницы в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id)
    {
        // Получить из БД страницу с указанным id
        $model = Page::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
