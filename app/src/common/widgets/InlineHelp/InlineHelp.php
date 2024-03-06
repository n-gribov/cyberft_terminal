<?php

namespace common\widgets\InlineHelp;

use Yii;
use yii\base\Widget;
use common\modules\wiki\models\Page;
use common\modules\wiki\models\WikiWidget;
use yii\helpers\ArrayHelper;
use common\models\CommonUserExt;

class InlineHelp extends Widget
{
    public $widgetId;
    public $setClassList = [];
    protected $classList = [];
    protected $articleTitle;
    protected $articleId;
    protected $sectionId = null;
    protected $error = false;
    protected $articlesList = [];
    protected $sectionsList = [];
    protected $canManage = false;

    public function init()
    {
        // Регистрация ассетов
        InlineHelpAsset::register($this->getView());

        $widget = WikiWidget::findOne(['widgetId' => $this->widgetId]);

        // Нет виджета
        if (empty($widget)) {
            $this->error = true;
        } else {
            $page = Page::find()->where(['id' => $widget->pageId])->orderBy(['version' => SORT_DESC])->one();

            // Нет страницы
            if (empty($page)) {
                $this->error = true;
            } else {
                // Мета-данные статьи
                $this->articleTitle = $page->title;

                // Если у страницы указан
                if ($page->pid) {
                    $this->sectionId = $page->pid;

                    // Id статьи
                    $this->articleId = $widget->pageId;

                    // Получение списка всех статей по категории
                    $articles = Page::find()->select(['id', 'title'])->where(['pid' => $page->pid])->asArray()->all();
                    $this->articlesList = ArrayHelper::map($articles, 'id', 'title');
                } else {
                    $this->sectionId = $page->id;
                }
            }
        }

        // Классы, которые должны быть установлены по-умолчанию
        $defaultClassList = [
            'inline-widget-button',
            'glyphicon',
            'glyphicon-question-sign'
        ];

        // Получаем общий список классов,
        // которые должны быть применены к кнопке помощи
        $this->classList = array_merge($defaultClassList, $this->setClassList);

        // Получаем список всех родительских статей для выбора
        $sections = Page::find()->select(['id', 'title'])->where(['pid' => ''])->asArray()->all();
        $this->sectionsList = ArrayHelper::map($sections, 'id', 'title');

        // Проверяем возможность пользователя управлять содержимым виджета
        if (!empty(Yii::$app->user) && !empty(Yii::$app->user->identity)) {
            $userSetting = CommonUserExt::findOne([
                'type' => CommonUserExt::DOCUMENTATION_WIDGETS,
                'userId' => Yii::$app->user->id,
                'canAccess' => 1
            ]);

            $this->canManage = !empty($userSetting);
        }

        parent::init();
    }

    public function run()
    {
        return $this->render('index', [
            'articleTitle' => $this->error ? 'Виджет раздела помощи' : $this->articleTitle,
            'classList' => $this->classList,
            'widgetId' => $this->widgetId,
            'error' => $this->error,
            'articleId' => $this->articleId,
            'sections' => $this->sectionsList,
            'sectionId' => $this->sectionId,
            'articles' => $this->articlesList,
            'canManage' => $this->canManage
        ]);
    }
}