<?php
namespace common\widgets;

use Yii;
use yii\base\Exception;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class AdvancedTabs extends Widget
{
    public $data = [];
    public $notFoundTabContent = 'The requested tab could not be found';
    public $params = [];
    public $actionStatus;
    public $currentUrl;
    public $curUrlQuery;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->renderData();
    }

    private function renderData()
    {
        if (isset($this->data['action'])) {
            $this->currentUrl = $this->getCurrentUrl();

            if (Yii::$app->request->get($this->data['action'])) {
                $this->actionStatus = Yii::$app->request->get($this->data['action']);
            } else {
                $this->actionStatus = ($this->data['defaultTab']? : null);
            }

            $renderedData = $this->renderTabs($this->data['tabs']);
            $renderedData .= $this->renderContent();

            return $renderedData;
        } else {
            return null;
        }
    }

    private function renderTabs($tabs)
    {
        $renderedTabs = null;

        if (is_array($tabs) && count($tabs)) {
            foreach ($tabs as $tabId => $tabData) {
                $isVisible = !array_key_exists('visible', $tabData) || $tabData['visible'] == true;
                if (!$isVisible) {
                    continue;
                }

                if (isset($tabData['url'])) {
                    $url = Html::a(
                        $tabData['label'],
                        $tabData['url'],
                        isset($tabData['linkOptions']) ? $tabData['linkOptions'] : []
                    );
                } else {
                    $url = Html::a(
                        $tabData['label'],
                        $this->currentUrl . $this->getDelimiter()
                        . $this->data['action'] . '=' . $tabId
                    );
                }
                $isActive = ($this->actionStatus==$tabId ? 'class="active"' : null);
                $renderedTabs .=
                    '<li role="presentation" '. $isActive . '>'
                    . $url
                    . '</li>';
            }

            if ($renderedTabs) {
                $renderedTabs = '<ul class="nav nav-tabs">' . $renderedTabs . '</ul>';
            }
        }

        return $renderedTabs;
    }

    private function renderContent()
    {
        $content = null;

        if (!isset($this->data['tabs'][$this->actionStatus])
            || !is_array($this->data['tabs'][$this->actionStatus])
            || !isset($this->data['tabs'][$this->actionStatus]['content'])
        ) {
            $content = $this->notFoundTabContent;
        } else {
            $content = $this->data['tabs'][$this->actionStatus]['content'];
            $viewPath = Yii::getAlias($content) . '.php';

            if (is_file($viewPath) && is_readable($viewPath)) {
                try {
                    // Вывести контент
                    $content = $this->render($content, $this->params);
                } catch (Exception $ex) {
                    // Поместить в сессию флаг сообщения об ошибке
                    Yii::$app->session->setFlash('error', $ex->getMessage());
                }
            }
        }

        return $content;
    }

    private function getDelimiter()
    {
        return empty($this->curUrlQuery) ? '?' : '&';
    }

    private function getCurrentUrl()
    {
        $curUrl = parse_url(Url::to(''));
        $this->curUrlQuery = [];
        if (isset($curUrl['query'])) {
            parse_str($curUrl['query'], $this->curUrlQuery);
            unset($this->curUrlQuery[$this->data['action']]);
        }

        return empty($this->curUrlQuery)
            ? $curUrl['path']
            : $curUrl['path'] . '?' . http_build_query($this->curUrlQuery);
    }
}
