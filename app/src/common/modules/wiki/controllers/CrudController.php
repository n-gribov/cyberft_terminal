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

    public function actionIndex()
    {
        $searchModel  = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
                [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['update', 'id' => $model->id]);
        } else {

            return $this->render('create',
                    [
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

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $result;
    }

    public function actionImagesList($pageId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = Page::findOne($pageId);
        if (empty($page)) {
            throw new NotFoundHttpException();
        }

        $result = [];
        if (!empty($page->attachments)) {
            foreach ($page->attachments as $attach) {
                $url      = Url::toRoute([
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->addFlash('success', WikiModule::t('default', 'Page updated'));

            return $this->redirect('');
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

//        if ($model->hasChildDocs()) {
//            Yii::$app->session->setFlash(
//                    'error',
//                    WikiModule::t('default', 'Page has related records and can not be deleted')
//            );
//
//            return $this->redirect(Yii::$app->request->referrer);
//        }

        $deleteList = $this->getRecursiveDeleteList($model);
        $attachmentList = Attachment::findAll(['page_id' => $deleteList]);

        foreach($attachmentList as $attachment) {
            $attachment->delete();
        }

        Page::deleteAll(['id' => $deleteList]);

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

    private function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAttachmentUpdate($id)
    {
        $model = Attachment::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', WikiModule::t('default', 'Attachment updated'));

            return $this->redirect('');
        } else {
            return $this->render('attachment/update', [
                    'model' => $model,
            ]);
        }
    }

    public function actionAttachmentDelete($id)
    {
        $model = Attachment::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException();
        }

        $pageId = $model->page->id;

        if (!$model->delete()) {
            Yii::$app->session->addFlash('error', WikiModule::t('default', 'Cannot delete attachment'));
            return $this->redirect('');
        }

        Yii::$app->session->addFlash('success', WikiModule::t('default', 'Attachment deleted'));
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

            $model->save();
        }

        if (!$model->isReady()) {

            return $this->render('export-wait');
        } else {

            return \Yii::$app->response->sendStreamAsFile(
                    $model->getTargetStream(), $model->getTargetFilename(),
                    [
                    'mimeType' => $model->getTargetMimeType(),
                    ]
            );
        }
    }

    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('file');
            if (empty($file)) {
                throw new \yii\web\BadRequestHttpException();
            }

            $result = FileHelper::storeTempFile($file->tempName, $file->name);

            if (false === $result) {
                Yii::$app->session->addFlash('error', WikiModule::t('default', 'Cannot store temp file'));
            } else {
                Yii::$app->resque->enqueue('common\modules\wiki\jobs\ImportJob', [
                    'tempFile'  => $result,
                ]);
                Yii::$app->session->addFlash('success', WikiModule::t('default', 'Archive uploaded, work in progress'));
            }
            
            return $this->redirect('');
        }

        return $this->render('import', [
        ]);
    }
}