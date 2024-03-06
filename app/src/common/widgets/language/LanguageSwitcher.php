<?php

namespace common\widgets\language;

use Yii;
use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class LanguageSwitcher extends Dropdown
{
    /**
     * @var string drop down button label
     */
    public $label = 'Language';

    /**
     * Функция инициализирует виджет.
     * Устанавливает название текущего языка как метку для кнопки смены языка.
     */
    public function init()
    {
        parent::init();

        $languages = isset(Yii::$app->getUrlManager()->languages) ? Yii::$app->getUrlManager()->languages : [];
        if (is_array($languages)) {
            $langArray = array_flip($languages);
            if (!empty(Yii::$app->language) && array_key_exists(Yii::$app->language, $langArray)) {
                $this->label = 'English'; //Yii::t('app', 'English'); //$langArray[Yii::$app->language]);
            } else {
                $this->label = Yii::t('app', Yii::$app->language);
            }
        }
    }

    /**
     * Renders the language drop down if there are currently more than one languages in the app.
     * If you pass an associative array of language names along with their code to the URL manager
     * those language names will be displayed in the drop down instead of their codes.
     */
    public function run()
    {
        $languages = isset(Yii::$app->getUrlManager()->languages) ? Yii::$app->getUrlManager()->languages : [];

        if (count($languages) > 1) {
            $items = [];
            $currentUrl = preg_replace('/' . Yii::$app->language . '\//', '', Yii::$app->getRequest()->getUrl(), 1);
            $isAssociative = ArrayHelper::isAssociative($languages);

            foreach ($languages as $language => $code) {

                $url = '/' . $code . $currentUrl;
                if ($isAssociative) {
                    $item = ['label' => Yii::t('app', $language), 'url' => $url];
                } else {
                    $item = ['label' => Yii::t('app', $code), 'url' => $url];
                }
                Html::addCssClass($item['options'], 'text-left');

                if ($code === Yii::$app->language) {
                    Html::addCssClass($item['options'], 'disabled');
                }
                $items[] = $item;
            }
            $this->items = $items;

            foreach ($items as $item) {
                echo Html::tag('li', Html::a($item['label'], $item['url'], ['class' => $item['options']['class']]));
            }

        }
        BootstrapPluginAsset::register($this->getView());
        $this->registerClientEvents();

    }
}
