<?php

use common\widgets\AdvancedTabs;
use yii\helpers\Url;

$this->title = Yii::t('edm', 'Currency operations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'action' => 'tabMode',
    'defaultTab' => $defaultTab,
    /** @todo @fixme
     * Параметр 'url' вставляется для предотвращения следующего кейса:
     * создается документ из шаблона, при этом мы попадаем в журнал с параметром templateId в url,
     * что является сигналом для открытия модалки. Если закрыть модалку, параметр templateId удаляется
     * из url в текущей вкладке, но остается во всех остальных. Поэтому, если перейти на другую вкладку,
     * модалка снова появится. Указание параметра 'url' в виджете для каждой вкладки предотвращает появление
     * templateId.
     * Сам механизм передачи templateId крайне кривой и нуждается в переработке.
     *
     */
    'tabs' => [
        'tabPurchase' => [
            'label' => Yii::t('edm', 'Currency purchase'),
            'content' => '@addons/edm/views/documents/fcoJournal',
            'url' => '?tabMode=tabPurchase',
            'visible' => in_array('tabPurchase', $availableTabs),
        ],
        'tabSell' => [
            'label' => Yii::t('edm', 'Currency sell'),
            'content' => '@addons/edm/views/documents/fcoJournal',
            'url' => '?tabMode=tabSell',
            'visible' => in_array('tabSell', $availableTabs),
        ],
        'tabPain001' => [
            'label' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            'content' => '@addons/edm/views/documents/fcoJournal',
            'url' => '?tabMode=tabPain001',
            'visible' => in_array('tabPain001', $availableTabs),
        ],
        'tabCurrencyConversion' => [
            'label' => Yii::t('edm', 'Currency conversion'),
            'content' => '@addons/edm/views/documents/fcoJournal',
            'url' => '?tabMode=tabCurrencyConversion',
            'visible' => in_array('tabCurrencyConversion', $availableTabs),
        ],
    ],
];

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'
        . Yii::t('app/error', 'The requested page could not be found.')
        . '</div>',
    'params' => [
        'dataProvider' => $dataProvider,
        'filterModel'  => $filterModel,
        'filterStatus' => $filterStatus,
        'banksFilter' => $banksFilter,
        'view' => $view,
        'type' => $type,
        'wizardType' => $wizardType,
        'cachedEntries' => $cachedEntries,
        'listType' => $listType,
        'orgFilter' => $orgFilter,
        'accountFilter' => $accountFilter,
        'tabMode' => isset($tabMode) ? $tabMode : '',
        'documentTypeGroup' => $documentTypeGroup,
    ]
]);

$this->registerCss('
    .nav-tabs {
        margin-bottom: 15px;
    }
');
?>