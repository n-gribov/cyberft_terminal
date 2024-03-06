<?php

use common\document\DocumentPermission;
use common\helpers\Html;
use common\widgets\AdvancedTabs;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\InlineHelp\InlineHelp;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$this->title = Yii::t('app/menu', 'Documents for signing');

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    'var documentCount = Number(' . count($cachedEntries['entries']) . ');',
    View::POS_READY
);

$selected = Yii::t('app', 'Selected');

$script = <<<JS
    $('body').on('click', '[name="selection[]"]', function(e) {
        var entries = [
            {
                id: this.value,
                checked: $(this).is(':checked')
            },
        ];

        sendSaveEntriesRequest(entries);
    });

    function sendSaveEntriesRequest(entries)
    {
        $.post(
            'select-entries?tabMode={$tabMode}',
            {
                entries: entries
            },

            function(data) {
                var selectedIds = JSON.parse(data);
                documentCount = selectedIds.length;
                showCheckedLabel(documentCount);
            }
        );
    };

    $('.select-on-check-all').click(function(e) {
        // костыль для ie
        $('[name="selection[]"]:visible:enabled').prop('checked', $(this).is(':checked'));

        var entries = $('[name="selection[]"]').map(
            function(index, element) {
                return {
                    id: element.value,
                    checked: $(element).is(':checked')
                };
            }
        ).get();

        sendSaveEntriesRequest(entries);
    });

    function showCheckedLabel(checkedQty)
    {
        // Если выбраны элементы, отображаем их количество, иначе обнуляем и скрываем
        if (checkedQty > 0) {
            $('.checked-signing-label').css({'display': 'inline-block'}).html('{$selected} ' + checkedQty);
            $('#sign-documents-button').removeClass('disabled');
        } else {
            $('.checked-signing-label').css({'display': 'none'}).html('');
            $('#sign-documents-button').addClass('disabled');
        }
    }

    showCheckedLabel(documentCount);

    $('body').on('click', '.disabled', function(e) {
        e.preventDefault();
    });

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    if (getUrlParameter('page')) {
        window.location.href = '/edm/documents/signing-index?tabMode=tabCFO';
    }

    stickyTableHelperInit();
JS;

$this->registerJs($script, View::POS_READY);

$userCanSignDocuments = \Yii::$app->user->can(
    DocumentPermission::SIGN,
    [
        'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
        'documentTypeGroup' => $documentTypeGroups,
    ]
);
$userCanDeleteDocuments = \Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
        'documentTypeGroup' => $documentTypeGroups,
    ]
);
$showActionButtons = ($userCanDeleteDocuments || $userCanSignDocuments) && $dataProvider->count > 0;
if ($showActionButtons) {
    if ($userCanSignDocuments) {
        echo SignDocumentsButton::widget([
            'buttonText' => Yii::t('app/message', 'Signing'),
            'prepareDocumentsIds' => new JsExpression("function (signCallback) { $.get('get-selected-entries-ids?tabMode={$tabMode}', function (ids) { signCallback(ids); }) }"),
            'entriesSelectionCacheKey' => $controllerCacheKey
        ]);
    }
    if ($userCanDeleteDocuments && $dataProvider->count > 0) {
        echo DeleteSelectedDocumentsButton::widget([
            'checkboxesSelector' => '.checkbox-selection, .select-on-check-all',
            'deleteUrl' => '/edm/documents/delete',
            'entriesSelectionCacheKey' => $controllerCacheKey
        ]);
    }
    echo '<div class="checked-signing-label label label-success"></div>';
}
?>

<p class="pull-right">
    <?php

    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );

    echo InlineHelp::widget(['widgetId' => 'edm-for-signing-journal', 'setClassList' => ['edm-journal-wiki-widget']]);
    ?>
</p>

<?php

$data = [
    'action' => 'tabMode',
    'defaultTab' => $availableTabs[0],
    'tabs' => [
        'tabRegisters' => [
            'label' => Yii::t('edm', 'Rouble payments') . ' (' . $forSigningCount['tabRegisters'] . ')',
            'content' => '@addons/edm/views/documents/_forsigningPaymentRegister',
            'visible' => in_array('tabRegisters', $availableTabs),
        ],
        'tabCurrencyPayments' => [
            'label' => Yii::t('edm', 'Currency payments') . ' (' . $forSigningCount['tabCurrencyPayments'] . ')',
            'content' => '@addons/edm/views/documents/_forsigningCurrencyPayment',
            'visible' => in_array('tabCurrencyPayments', $availableTabs),
        ],
        'tabFCOSigning' => [
            'label' => Yii::t('edm', 'Currency operations') . ' (' . $forSigningCount['tabFCOSigning'] . ')',
            'content' => '@addons/edm/views/documents/_forsigningFCO',
            'visible' => in_array('tabFCOSigning', $availableTabs),
        ],
        'tabFCCSigning' => [
            'label' => 'СВО (' . $forSigningCount['tabFCCSigning'] . ')',
            'content' => '@addons/edm/views/foreign-currency-control/_forSigning',
            'visible' => in_array('tabFCCSigning', $availableTabs),
        ],
        'tabCDISigning' => [
            'label' => 'СПД (' . $forSigningCount['tabCDISigning'] . ')',
            'content' => '@addons/edm/views/confirming-document-information/_forSigning',
            'visible' => in_array('tabCDISigning', $availableTabs),
        ],
        'tabCRRSigning' => [
            'label' => Yii::t('edm', 'Contract requests') . ' (' . $forSigningCount['tabCRRSigning'] . ')',
            'content' => '@addons/edm/views/contract-registration-request/_forSigning',
            'visible' => in_array('tabCRRSigning', $availableTabs),
        ],
        'tabBankLetters' => [
            'label' => Yii::t('edm', 'Letters') . ' (' . $forSigningCount['tabBankLetters'] . ')',
            'content' => '@addons/edm/views/bank-letter/_forSigning',
            'visible' => in_array('tabBankLetters', $availableTabs),
        ],
        'tabStatementRequests' => [
            'label' => 'Запросы выписки (' . $forSigningCount['tabStatementRequests'] . ')',
            'content' => '@addons/edm/views/documents/_forsigningStatementRequest',
            'visible' => in_array('tabStatementRequests', $availableTabs),
        ],
        'tabVTBCancellationRequests' => [
            'label' => 'Запросы на отзыв (' . $forSigningCount['tabVTBCancellationRequests'] . ')',
            'content' => '@addons/edm/views/documents/_forsigningVTBCancellationRequest',
            'visible' => in_array('tabVTBCancellationRequests', $availableTabs) && Yii::$app->user->can('vtbDocuments'),
        ],
    ],
];

$this->registerCss('
    .checked-signing-label {
        margin-left: 1em;
        display: none;
    }

    .disabled {
        pointer-events: auto !important;
    }
');

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'
        . Yii::t('app/error', 'The requested page could not be found.')
        . '</div>',
    'params' => [
        'dataProvider' => $dataProvider,
        'filterModel'  => $model,
        'cachedEntries' => $cachedEntries,
        'listType' => $listType,
        'orgFilter' => $orgFilter,
        'accountFilter' => $accountFilter,
        'userCanSignDocuments' => $userCanSignDocuments,
    ]
]);

?>
