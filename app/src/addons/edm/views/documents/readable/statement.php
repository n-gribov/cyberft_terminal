<?php

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Statement\StatementOperationSearch;
use addons\edm\models\Statement\StatementTypeConverter;
use common\document\Document;
use common\helpers\DateHelper;
use common\helpers\Html;
use common\models\cyberxml\CyberXmlDocument;
use common\widgets\FastPrint\FastPrint;
use common\widgets\GridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kop\y2sp\ScrollPager;
use yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\MaskedInput;

/* @var $this View */
/* @var $model Document */

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    $content = StatementTypeConverter::convertFrom($typeModel);
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';

    return;
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';

    return;
}

$payerAccount = EdmPayerAccount::findOne(['number' => $content->statementAccountNumber]);

// Печать выписки
$printUrl = Url::toRoute(['/edm/documents/print', 'id' => $model->id]);
$printBtn = '.print-statement';
$documentId = $model->id;

echo FastPrint::widget([
    'printUrl' => $printUrl,
    'printBtn' => $printBtn,
    'documentId' => $documentId
]);

// Печать всех операций выписки
$printUrl = Url::toRoute(['/edm/documents/print-all', 'id' => $model->id]);
$printBtn = '.print-statement-all-transactions';

echo FastPrint::widget([
    'printUrl' => $printUrl,
    'printBtn' => $printBtn,
    'documentId' => $documentId
]);

if (!$payerAccount) {
    echo 'Выписка по неизвестному счету';
    return;
}

$dataProvider = new ArrayDataProvider([
	'allModels' => $content->transactions,
	'pagination' => [
		'pageSize' => 20,
	],
]);

$searchModel = new StatementOperationSearch();
$queryParams = Yii::$app->request->queryParams;
$dataProvider = $searchModel->search($queryParams, $dataProvider);

$statementPeriodStart = new DateTime($content->statementPeriodStart);
$statementPeriodEnd = new DateTime($content->statementPeriodEnd);
?>

<div>

<table class="info-table">
    <tr>
        <td>Владелец счета</td>
        <td colspan="8"><strong><?=$payerAccount->getPayerName()?></strong></td>
    </tr>
    <tr>
        <td style="width: 115px;">
            Счет
        </td>
        <td style="width: 200px;">
            <strong><?= $content->statementAccountNumber ?></strong>
        </td>
        <td style="width: 85px;"><strong>Остатки</strong></td>
        <td style="width: 110px;"></td>
        <td style="width: 70px;"><strong>Обороты</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>Валюта счета</td>
        <td><strong><?= $payerAccount->edmDictCurrencies->name ?></strong></td>
        <td>Входящий</td>
        <td>
            <strong>
                <?= number_format($content->openingBalance, 2, '.', ' ') ?>
            </strong>
        </td>
        <td>Дебет</td>
        <td style="width: 117px;">
            <strong>
                <?= number_format($content->debitTurnover, 2, '.', ' ') ?>
            </strong>
        </td>
        <td style="width: 181px;">Период выписки</td>
        <td>
            <strong>
                <?= $content->statementPeriodStart ?> - <?= $content->statementPeriodEnd ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>Банк</td>
        <td><strong><?= Html::encode($payerAccount->bank->name) ?></strong></td>
        <td>Исходящий</td>
        <td>
            <strong>
                <?= number_format($content->closingBalance, 2, '.', ' ') ?>
            </strong>
        </td>
        <td>Кредит</td>
        <td>
            <strong>
                <?= number_format($content->creditTurnover, 2, '.', ' ') ?>
            </strong>
        </td>
<?php if (isset($content->prevLastOperationDate)): ?>
        <td>Дата последней операции</td>
        <td><strong><?= date('d.m.Y', strtotime($content->prevLastOperationDate)) ?></strong></td>
<?php endif; ?>
    </tr>
</table>

<?php
if ($content->accountRestrictions !== null) {
    if (count($content->accountRestrictions) === 0) {
        echo '<p>Ограничения по счету: <b>Ограничений нет</b></p>';
    } else {
        echo '<p>Ограничения по счету: '
            . '<span style="font-weight: bold; color: red; text-decoration: underline; cursor: pointer;" onclick="restrictions_view_modal()">Имеются ограничения</span>'
            . '</p>';
    }
}
?>

<?php if ($content->signatureCardExpirationDate != ''): ?>
    <p>Срок действия карточки образцов подписи до <b><?= $content->signatureCardExpirationDate ?></b></p>
<?php endif; ?>

<?= GridView::widget([
    'emptyText' => '',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($paymentNumber) use ($model) {
        $options['onclick'] = "statement_content_view_modal(" . $model->id . ", '" . $paymentNumber['Number'] . "', '" .       $paymentNumber['UniqId'] . "')";
        return $options;
    },
    'pager' => [
        'class' => ScrollPager::className(),
        'container' => '.grid-view tbody',
        'item' => 'tr',
        'paginationSelector' => '.grid-view .pagination',
        'noneLeftText' => '',
        'triggerOffset' => 999,
        'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>'
    ],
    'columns' => [
        [
            'attribute' => 'ValueDate',
            'filter' => kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'ValueDate',
                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'orientation' => 'bottom'
                ],
                'options' => [
                    'id' => 'value-date',
                    'class' => 'form-control',
                    'style'     => 'width: 95px',
                ]
            ]),
            'label' => Yii::t('doc/st', 'Date'),
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'contentOptions' => [
                'style' => 'text-align: right; width: 20px;',
                'nowrap' => 'nowrap'
            ],
            'value' => function($model) {
                return DateHelper::formatDate($model['ValueDate'], 'date');
            },
        ],
        [
            'attribute' => 'Number',
            'enableSorting' => false,
            'label' => Yii::t('doc/st', 'Number'),
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'contentOptions' => [
                'style' => 'text-align: right; width: 70px;',
            ],
            'filter' => MaskedInput::widget([
                'attribute'     => 'Number',
                'model' => $searchModel,
                'clientOptions' => [
                    'alias' => 'decimal',
                ]
            ]),
        ],
        [
            'attribute' => 'PayerAccountNum',
            'enableSorting' => false,
            'format' => 'raw',
            'label' => Yii::t('edm', 'Payer'),
            'value' => function($model) {
                return $model['PayerAccountNum']
                    . '<br/>' . $model['PayerName']
                    . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayerINN']
                    . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayerKPP'];
            },
            'contentOptions' => [
                'style' => 'max-width: 180px;'
            ],
        ],
        [
            'attribute' => 'PayerBIK',
            'enableSorting' => false,
            'format' => 'raw',
            'label' => Yii::t('edm', 'Payer Bank'),
            'value' => function($model) {
                return Yii::t('doc/st', 'BIK') . ': ' . $model['PayerBIK']
                    . '<br/>' . $model['PayerBankName']
                    . '<br/>' . $model['PayerBankAccountNum'];
            },
            'contentOptions' => [
                'style' => 'width: 180px;'
            ],
        ],
        [
            'attribute' => 'PayeeName',
            'enableSorting' => false,
            'format' => 'raw',
            'label' => Yii::t('edm', 'Payee'),
            'value' => function($model) {
                return $model['PayeeAccountNum']
                    . '<br/>' . $model['PayeeName']
                    . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayeeINN']
                    . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayeeKPP'];
            },
            'contentOptions' => [
                'style' => 'width: 180px;'
            ],
        ],
        [
            'attribute' => 'PayeeBIK',
            'enableSorting' => false,
            'format' => 'raw',
            'label' => Yii::t('edm', 'Payee Bank'),
            'value' => function($model) {
                return Yii::t('doc/st', 'BIK') . ': ' . $model['PayeeBIK']
                    . '<br/>' . $model['PayeeBankName']
                    . '<br/>' . $model['PayeeBankAccountNum'];
            },
            'contentOptions' => [
                'style' => 'width: 180px;'
            ],
        ],
        [
            'attribute' => 'Debit',
            'enableSorting' => false,
            'value' => function($row) {
                return Yii::$app->formatter->asDecimal($row['Debit'], 2);
            },
            'label' => Yii::t('doc/st', 'Debit'),
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'contentOptions' => [
                'style' => 'text-align: right; min-width: 79px',
                'nowrap' => 'nowrap'
            ],
            'filter' => MaskedInput::widget([
                'attribute'     => 'Debit',
                'model' => $searchModel,
                'clientOptions' => [
                    'alias' => 'decimal',
                    'digits' => 2,
                    'digitsOptional' => false,
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                    'placeholder' => '0.00',
                    'groupSeparator' => ' '
                ]
            ]),
        ],
        [
            'attribute' => 'Credit',
            'enableSorting' => false,
            'value' => function($row) {
                return Yii::$app->formatter->asDecimal($row['Credit'], 2);
            },
            'label' => Yii::t('doc/st', 'Credit'),
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'contentOptions' => [
                'style' => 'text-align: right; min-width: 79px',
                'nowrap' => 'nowrap'
            ],
            'filter' => MaskedInput::widget([
                'attribute'     => 'Credit',
                'model' => $searchModel,
                'clientOptions' => [
                    'alias' => 'decimal',
                    'digits' => 2,
                    'digitsOptional' => false,
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                    'placeholder' => '0.00',
                    'groupSeparator' => ' '
                ]
            ]),
        ],
        [
            'attribute' => 'Purpose',
            'enableSorting' => false,
            'label' => Yii::t('doc/st', 'Purpose'),
            'contentOptions' => [
                'style' => 'max-width: 373px'
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{viewSwiftDocument}',
            'buttons' => [
                'viewSwiftDocument' => function ($url, $model, $key) {
                    if (!array_key_exists('SWIFTMessage', $model) || empty($model['SWIFTMessage'])) {
                        return '';
                    }
                    $message = json_encode($model['SWIFTMessage']);
                    return Html::a(
                        '<span class="ic-globus"></span>',
                        '#',
                        [
                            'class' => 'show-swift-message-button',
                            'title' => Yii::t('edm', 'Show SWIFT-document'),
                            'data' => ['swift-message' => $message],
                        ]
                    );
                }
            ],
            'contentOptions' => ['class' => 'text-right']
        ],
    ],
]);
?>

</div>

<?php

echo ToTopButtonWidget::widget();

$this->registerCss('
    #statement-content-modal .modal-dialog {
        width: 1000px;
    }

    #restrictions-modal .modal-dialog {
        width: 1000px;
    }

    .info-table {
        margin-bottom: 20px;
    }

    .info-table td {
        padding: 2px;
    }

   .modal-table th, .modal-table td {
        border: 1px solid #eee;
        padding: 10px;
    }

    table.floatThead-table {
        border-top: none;
        border-bottom: none;
        background-color: #FFF;
    }

    .tableFloatingHeaderOriginal {
        background-color: #fff;
    }

    #statement-content-modal .modal-body {
        min-height: 840px;
    }

    #statement-content-modal .loading-message {
        left: 50%;
        top: 50%;
        position: fixed;
        transform: translate(-50%, -50%);
    }
');

$script = <<< JS
    stickyTableHelperInit();

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
        window.location.href = '/edm/documents/view?id=' + getUrlParameter('id')  + '&mode=source';
    }

    $('.show-swift-message-button').click(function () {
        var message = JSON.parse($(this).data('swift-message'));
        var pre = $('<pre id="swift-message-content">').html(message);
        $('#swift-message-content').replaceWith(pre);
        $('#swift-message-modal').modal('show');
        return false;
    });

    // Маска для ввода значения дат
    $('#value-date').inputmask('99.99.9999', {placeholder:'дд.мм.гггг'});
JS;

$this->registerJs($script, yii\web\View::POS_READY);

// Модальное окно для отображения содержимого выписки
$header = '<h4 class="modal-title" id="myModalLabel"></h4>';

$modal = Modal::begin([
    'id' => 'statement-content-modal',
    'header' => $header,
    'footer' => null,
]); ?>

<?php $modal::end(); ?>

<?php Modal::begin([
    'id' => 'swift-message-modal',
    'header' => '<h4 class="modal-title">' . Yii::t('edm', 'Attached SWIFT-document') . '</h4>',
    'footer' => null,
]); ?>

<pre id="swift-message-content"></pre>

<?php $modal::end(); ?>

<?php if (!empty($content->accountRestrictions)): ?>
    <?php Modal::begin([
            'id' => 'restrictions-modal',
            'header' => '<h4 class="modal-title">' . Yii::t('edm', 'Account restrictions') . '</h4>',
            'footer' => null,
        ]); ?>
        <table class="modal-table" width="100%">
            <tr>
                <th>Основание ограничения</th>
                <th>Тип</th>
                <th class="text-right">Дата наложения</th>
                <th class="text-right" >Cумма</th>
            </tr>
            <?php foreach ($content->accountRestrictions as $restriction): ?>
                <tr>
                    <td><?=$restriction['Descr']?></td>
                    <td><?=$restriction['RestrType']?></td>
                    <td class="text-right"><?= $restriction['RestrDate'] ?></td>
                    <td class="text-right"><?= \Yii::$app->formatter->asDecimal($restriction['Amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php $modal::end(); ?>
<?php endif; ?>

<script>
    function statement_content_view_modal(id, paymentNumber, uniqId) {
        $('#statement-content-modal .modal-title').html('Платежное поручение №' + paymentNumber);
        $('#statement-content-modal .modal-body').html('<div class="loading-message"><img src="/img/spinner.gif"/> <?= Yii::t('app', 'Loading...') ?></div>');

        $.ajax({
            url: '/edm/documents/get-statement-content-view',
            type: 'get',
            data: {'id': id, 'uniqId': uniqId},
            success: function(answer){
                $('#statement-content-modal .modal-body').html(answer);
            }
        });

        $('#statement-content-modal').modal('show');
    }

    function restrictions_view_modal() {
        $('#restrictions-modal').modal('show');
    }
</script>
