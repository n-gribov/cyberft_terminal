<?php

namespace common\modules\wiki\controllers;

use common\base\Controller as BaseController;
use common\modules\wiki\models\Attachment;
use common\modules\wiki\models\Page;
use common\modules\wiki\models\WikiWidget;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class DefaultController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // Accessible for authorized users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $pages = Page::find()->where(['pid' => 0])->orderBy('title')->all();

        return $this->render('index', ['pages' => $pages]);
    }

    public function actionView($slug, $parents = '' ,$version = '')
    {

        // если в урле не передается версия документа, то достаем последнюю версию из бд
        if (!empty($version)) {
            $page = Page::find()->where(['slug' => $slug, 'version' => $version])->one();
        } else {
            $page = Page::find()->where(['slug' => $slug])->orderBy(['version' => SORT_DESC])->one();
        }
        
        if (is_null($page)) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        if ($parents) {
            $arrParents = explode('/',$parents);

            $slug = [];

            if (!empty($pageParents = $page->parents)) {
                krsort($pageParents);

                foreach ($pageParents as $parent) {
                    $slug[] = $parent->slug;
                }
            }

            $diff = array_diff_assoc($arrParents,$slug);

            if (!empty($diff)) {
                throw new NotFoundHttpException('The requested page does not exist');
            }
        }

        return $this->render('view', ['model' => $page]);
    }

    public function actionDownload($id)
    {
        if (($attachFile = Attachment::findOne($id))) {
            $attachFilePath = Yii::getAlias('@storage/docs/attachment/') . $attachFile->file_name;
            if (is_file($attachFilePath)) {
                Yii::$app->response->sendFile(Yii::getAlias('@storage/docs/attachment/') . $attachFile->file_name);
            }
        }
    }

    public function actionAttachmentDownload($id, $inline)
    {
        $model = Attachment::findOne($id);

        if (empty($model) || empty($stream = $model->getFileStream())) {
            throw new NotFoundHttpException();
        }

        return \Yii::$app->response->sendStreamAsFile(
            $stream,
            pathinfo($model->path, PATHINFO_BASENAME),
            [
                'mimeType' => $model->getMimeType(),
                'inline' => !empty($inline)
            ]
        );
    }

    /**
     * Связывание статьи и виджета
     */
    public function actionSetArticleWidgetId()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException('404','Method not found');
        }

        // Записываем связку в таблицу
        $get = Yii::$app->request->get();

        // Возвращаем ошибку, если не найден id виджета
        if (!isset($get['widgetId'])) {
            $answer = [
                'status' => 'error',
                'msg' => 'Не указан номер виджета справки'
            ];

            return json_encode($answer);
        }

        // Получаем пришедшие get-параметры
        $widgetId = $get['widgetId'];
        $pageId = $get['pageId'];

        // Находим существующую связку
        $widget = WikiWidget::find()->where(['widgetId' => $widgetId])->one();

        // Если связки не найдено, создаем новую
        if (!$widget) {
            $widget = new WikiWidget;
            $widget->widgetId = $widgetId;
        }

        $widget->pageId = $pageId;

        // Если запись успешна, возвращаем удачный статус
        if ($widget->save()) {
            $answer = [
                'status' => 'ok'
            ];
        } else {
            $answer = [
                'status' => 'error',
                'msg' => 'Ошибка создания связи виджета справки и статьи в документации'
            ];
        }

        return json_encode($answer);
    }

    /**
     * Получение статей по по разделу
     * @return string
     * @throws \yii\web\HttpException
     */
    function actionGetSectionById()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException('404','Method not found');
        }

        $sectionId = Yii::$app->request->get('sectionId');

        // Если не передан id раздела
        if (!$sectionId) {
            return json_encode([]);
        }

        // Получаем информация по самому разделу
        $section = Page::find()->where(['id' => $sectionId])->orderBy(['version' => SORT_DESC])->asArray()->one();

        // Если ничего не найдено
        if (!$section) {
            return json_encode([]);
        }

        $articles = Page::find()->select(['id', 'title'])->where(['pid' => $sectionId])->asArray()->all();

        // Добавляем в ответ статьи раздела
        $section['articles'] = $articles;

        // Возвращаем результаты в ввиде json
        return json_encode($section);

    }

    function actionGetArticleById()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException('404','Method not found');
        }

        $articleId = Yii::$app->request->get('articleId');

        // Если не передан id статьи
        if (!$articleId) {
            return json_encode([]);
        }

        $page = Page::find()->where(['id' => $articleId])->orderBy(['version' => SORT_DESC])->asArray()->one();

        if ($page) {
            return json_encode($page);
        } else {
            return json_encode([]);
        }
    }
}