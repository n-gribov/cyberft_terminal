<?php
namespace backend\widgets;

use addons\edm\helpers\EdmHelper;
use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\Statement\StatementSearch;
use addons\fileact\models\FileActSearch;
use addons\finzip\models\FinZipSearch;
use addons\swiftfin\models\SwiftFinSearch;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Nav as BootstrapNav;
use yii\helpers\Html;
use yii\helpers\Url;

class Nav extends BootstrapNav
{
    private $currentItem = null;
    private $currentUrl;

    public function init()
    {
        parent::init();
        // Транслируем метки всех вложенных уровней
        $this->items = $this->translateItems($this->items);

        $this->currentUrl = Yii::$app->request->getUrl();
        $this->currentUrl = str_replace('/ru/','/',$this->currentUrl);
        $this->currentUrl = str_replace('/en/','/',$this->currentUrl);
        $this->currentUrl = strtok($this->currentUrl, '?');
        $this->currentUrl .= '/';
    }

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        $html = '';

        // Текущий контроллер
        if (isset($item['items']) and is_array($item['items'])) {
            // Пункты меню со вложенными элементами

            // Проверка, должен ли быть раскрытым обрабатываемый пункт меню
            if (is_null($this->currentItem)) {
                $pathId = '';

                if (isset($item['serviceID'])) {
                    $pathId = $item['serviceID'];
                } else if (isset($item['menuId'])) {
                    $pathId = $item['menuId'];
                }

                if (!is_array($pathId)) {
                    $pathId = [$pathId];
                }

                foreach ($pathId as $path) {
                    if (preg_match('/^\/' . $path. '\//', $this->currentUrl)) {
                        $this->currentItem = $item['id'];

                        break;
                    }
                }
            }

            $html = '<li><a href="#" ';

            if ($this->currentItem == $item['id']) {
                $html .= ' class="collapsed" ';
            }

            $html .= ' id="' . $item['id'] . '" data-toggle="next"><span class="'
                    . $item['iconClass'] . '"></span>'
                    . Yii::t('app/menu', $item['label']).'</a>';
            if ($this->currentItem  == $item['id']) {
                $html .= '<ul class="collapse in" aria-expanded="true">';
            } else {
                $html .= '<ul class="collapse">';
            }
            // Добавление пунктов подменю
            foreach ($item['items'] as $vv) {
                $label = $this->getItemLabel($vv);
                if ($vv['url'][0] == rtrim($this->currentUrl, '/')) {
                    $html .= Html::tag('li', Html::a(
                                $label,
                                Url::toRoute($vv['url'][0]),
                                ['class' => 'menu-active'])
                            );
                } else {
                    $html .= Html::tag('li', Html::a($label, Url::toRoute($vv['url'][0])));
                }
            }
            $html .= '</ul>';
            $html .= '</li>';
        } else {
            // Пункты меню без вложенных элементов
            $label = $this->getItemLabel($item);

            if ($item['url'][0] == rtrim($this->currentUrl, '/')) {
                $class = ' class="menu-active"';
            } else {
                $class = '';
            }

            $html .= '<li><a' . $class . ' href="' . Url::toRoute($item['url'][0])
                    . '" id="' . $item['id'] . '"><span class="' . $item['iconClass'] . '"></span>'
                    . $label . '</a></li>';
        }

        return $html;
    }
    /*
     * Функция транслирует метки вложенных уровней Nav-меню
     */

    protected function translateItems(&$items)
    {
        if ($items !== null && is_array($items)) {
            foreach ($items as &$item) {
                if (!isset($item['label'])) {
                    throw new InvalidConfigException("The 'label' option is required.");
                }

                $item['label'] = Yii::t('app/menu', $item['label']);
                // Транслируем метки вложенных уровней //
                $subItems      = (isset($item['items']) ? $item['items'] : null);
                $item['items'] = $this->translateItems($subItems);

                if (!empty($item['only'])){
                    $item['visible'] = (\Yii::$app->request->pathInfo === $item['only']);
                    unset($item['only']);
                }
            }
        }

        return $items;
    }

    /**
     * Формирование наименования пункта меню
     */
    protected function getItemLabel($item)
    {
        $label = Yii::t('app/menu', $item['label']);

        // Вывод дополнительной
        // информации для пункта меню
        if (isset($item['extData'])) {
            $extData = null;
            $badge = '';

            if (!Yii::$app->user->can('admin')) {
                if ($item['extData'] == 'newStatementCount') {
                    $extData = StatementSearch::getUnreadCount();
                } else if ($item['extData'] == 'newFinZipCount') {
                    $extData = FinZipSearch::getUnreadCount();
                } else if ($item['extData'] == 'forSigningEdmCount' && Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => 'edm'])) {
                    $extData = EdmHelper::getEdmForSigningCount();
                } else if ($item['extData'] == 'forSigningSwiftfinCount' && Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => 'swiftfin'])) {
                    $extData = SwiftFinSearch::getForSigningCount();
                } else if ($item['extData'] == 'forCorrectionSwiftfinCount') {
                    $extData = SwiftFinSearch::getForCorrectionCount();
                } else if ($item['extData'] == 'forVerificationSwiftfinCount') {
                    $extData = SwiftFinSearch::getForVerificationCount();
                } else if ($item['extData'] == 'forAuthorizationSwiftfinCount') {
                    $extData = SwiftFinSearch::getForAuthorizationCount();
                } else if ($item['extData'] == 'forSigningFileactCount' && Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => 'fileact'])) {
                    $extData = FileActSearch::getForSigningCount();
                } else if ($item['extData'] == 'forSigningFinzipCount' && Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => 'finzip'])) {
                    $extData = FinZipSearch::getForSigningCount();
                } else if ($item['extData'] == 'newBankLetterCount') {
                    $extData = BankLetterSearch::getUnreadCount();
                }

                if ($extData) {
                    $badge = ' <span class="badge badge-primary">' . $extData . '</span>';
                }
            }

            if ($item['extData'] == 'importErrorsCount') {
                $extData = DocumentHelper::getNewImportErrorsCount();
                if ($extData) {
                    $badge = ' <span class="badge badge-danger">' . $extData . '</span>';
                }
            }

            $label .= $badge;
        }

        return $label;
    }
}
