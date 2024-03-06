<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\vtb\VTBHelper;
use common\models\listitem\AttachedFile;
use common\modules\certManager\models\Cert;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\FastPrint\FastPrint;
use common\widgets\TransportInfo\TransportInfoButton;
use common\widgets\TransportInfo\TransportInfoModal;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var View $this */
/** @var LoanAgreementRegistrationRequestForm $model */
/** @var Document $document */

$this->title = Yii::t('edm', 'Loan agreement registration request');

function formatLoanAgreementUniqueNumber(LoanAgreementRegistrationRequestForm $model)
{
    return implode(
        ' / ',
        [
            $model->loanAgreementUniqueNumber1,
            $model->loanAgreementUniqueNumber2,
            $model->loanAgreementUniqueNumber3,
            $model->loanAgreementUniqueNumber4,
            $model->loanAgreementUniqueNumber5
        ]
    );
}

function formatPercentValue($value)
{
    return $value !== null || $value === ''
        ? number_format((float)$value, 2, '.', '') . ' %'
        : null;
}

?>

<div class="action-buttons-block">
    <?php
    echo Html::a(
        Yii::t('app', 'Back'),
        ['/edm/documents/foreign-currency-control-index', 'tabMode' => 'tabCRR'],
        ['class' => 'btn btn-default']
    );
    echo Html::button(Yii::t('app', 'Print'), ['id' => 'print-button', 'class' => 'btn btn-default']);

    $userCanCreateDocuments = Yii::$app->user->can(
        DocumentPermission::CREATE,
        [
            'serviceId' => EdmModule::SERVICE_ID,
            'documentTypeGroup' => EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
        ]
    );
    $isSendable = $userCanCreateDocuments && $document->status === Document::STATUS_CREATING && $document->signaturesRequired == 0;
    $isSignable = $document->isSignableByUserLevel(EdmModule::SERVICE_ID);
    $isCancellableVTBDocument = VTBHelper::isVTBDocument($document) && VTBHelper::isCancellableDocument($document);

    if ($userCanCreateDocuments && in_array($document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING])) {
        echo Html::a(
            Yii::t('app', 'Edit'),
            Url::to(['update', 'id' => $document->id]),
            ['class' => 'btn btn-primary']
        );
    }

    if ($isSendable) {
        echo Html::a(
            Yii::t('app', 'Send'),
            Url::to(['send', 'id' => $document->id]),
            ['class' => 'btn btn-success']
        );
    } elseif ($isSignable) {
        $buttonText = $document->signaturesCount == $document->signaturesRequired - 1
            ? Yii::t('document', 'Sign and send')
            : Yii::t('edm', 'Sign');

        echo SignDocumentsButton::widget([
            'buttonText' => $buttonText,
            'documentsIds' => [$document->id],
        ]);
    } elseif ($userCanCreateDocuments && $isCancellableVTBDocument) {
        echo Html::a(Yii::t('edm', 'Call off the document'),
            ['/edm/vtb-documents/view', 'id' => $document->id, 'triggerCancellation' => 1],
            ['class' => 'btn btn-danger']
        );
    }
    ?>

    <div class="pull-right">
        <?= TransportInfoButton::widget() ?>
    </div>
</div>

<h4>Сведения о резиденте</h4>
<?= DetailView::widget([
    'model' => $model->organization,
    'attributes' => [
        [
            'attribute' => 'name',
            'label' => 'Наименование'
        ],
        'inn',
        'kpp',
        [
            'attribute' => 'fullAddress',
            'label' => 'Адрес',
        ],
        [
            'attribute' => 'ogrn',
            'label' => 'Основной государственный регистрационный номер',
        ],
        [
            'attribute' => 'dateEgrul',
            'label' => 'Дата внесения записи в государственный реестр',
        ],
    ]
]) ?>

<h4>Реквизиты нерезидента (нерезидентов)</h4>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->nonResidents,
        'modelClass' => LoanAgreementRegistrationRequestForm\NonResident::class,
        'pagination' => false,
    ]),
    'columns' => [
        'name',
        'countryName',
        'countryCode',
    ],
    'layout' => '{items}',
]) ?>

<h4>Сведения о кредитном договоре</h4>
<h5>Общие сведения о кредитном договоре</h5>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'loanAgreementNumber',
            'label' => Yii::t('edm', 'Number'),
            'value' => $model->loanAgreementNumber ?: 'БН',
        ],
        [
            'attribute' => 'loanAgreementDate',
            'label' => 'Дата'
        ],
        [
            'attribute' => 'loanAgreementCurrencyCode',
            'value' => $model->loanAgreementCurrencyCode ? "{$model->loanAgreementCurrencyDescription} ($model->loanAgreementCurrencyCode)" : null,
        ],
        [
            'attribute' => 'loanAgreementAmount',
            'format' => ['decimal', 2],
        ],
        [
            'attribute' => 'loanAgreementEndDate',
            'label' => Yii::t('edm', 'Loan agreement obligations end date'),
        ],
        [
            'attribute' => 'foreignAccountsTransferAmount',
            'format' => ['decimal', 2],
        ],
        [
            'attribute' => 'currencyIncomeRepaymentAmount',
            'format' => ['decimal', 2],
        ],
        'repaymentPeriodName',
        [
            'attribute' => 'loanAgreementUniqueNumber',
            'label' => Yii::t('edm', 'Loan agreement unique number'),
            'value' => implode(
                ' / ',
                [
                    $model->loanAgreementUniqueNumber1,
                    $model->loanAgreementUniqueNumber2,
                    $model->loanAgreementUniqueNumber3,
                    $model->loanAgreementUniqueNumber4,
                    $model->loanAgreementUniqueNumber5
                ]
            ),
        ],
        'previousLoanAgreementUniqueNumber',
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<h5>Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору</h5>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->tranches,
        'modelClass' => LoanAgreementRegistrationRequestForm\Tranche::class,
        'pagination' => false,
    ]),
    'columns' => [
        [
            'label' => 'Валюта кредитного договора',
            'value' => function () use ($model) {
                return "{$model->loanAgreementCurrencyDescription} ($model->loanAgreementCurrencyCode)";
            }
        ],
        [
            'attribute' => 'amount',
            'format' => ['decimal', 2],
        ],
        'paymentPeriodName',
        'receiptDate',
    ],
    'layout' => '{items}',
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<h4>Специальные сведения о кредитном договоре</h4>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'fixedInterestRatePercent',
            'value' => function ($model, $params) {
                return formatPercentValue($model->fixedInterestRatePercent);
            }
        ],
        'fixedInterestRateLiborCodeName',
        'otherPercentRateCalculationMethod',
        [
            'attribute' => 'increaseRatePercent',
            'value' => function ($model, $params) {
                return formatPercentValue($model->increaseRatePercent);
            }
        ],
        'otherPayments',
        [
            'attribute' => 'mainDebtAmount',
            'format' => ['decimal', 2],
        ],
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<h4>Справочная информация о кредитном договоре</h4>
<h5>Описание графика платежей по возврату основного долга и процентных платежей</h5>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->paymentScheduleItems,
        'modelClass' => LoanAgreementRegistrationRequestForm\PaymentScheduleItem::class,
        'pagination' => false,
    ]),
    'columns' => [
        [
            'label' => 'Валюта кредитного договора',
            'value' => function () use ($model) {
                return "{$model->loanAgreementCurrencyDescription} ($model->loanAgreementCurrencyCode)";
            }
        ],
        'debtDate',
        [
            'attribute' => 'debtAmount',
            'format' => ['decimal', 2]
        ],
        'interestDate',
        [
            'attribute' => 'interestAmount',
            'format' => ['decimal', 2]
        ],
        'specialConditions',
    ],
    'layout' => '{items}',
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'paymentScheduleReasonName',
        [
            'attribute' => 'isDirectInvesting',
            'value' => $model->isDirectInvesting ? 'да' : 'нет',
        ],
        [
            'attribute' => 'depositAmount',
            'format' => ['decimal', 2],
        ],
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<h5>Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе</h5>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->receipts,
        'modelClass' => LoanAgreementRegistrationRequestForm\Receipt::class,
        'pagination' => false,
    ]),
    'columns' => [
        'beneficiaryName',
        'beneficiaryCountryName',
        [
            'attribute' => 'amount',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'shareOfLoanAmount',
            'value' => function ($model, $params) {
                return formatPercentValue($model->shareOfLoanAmount);
            }
        ],
    ],
    'layout' => '{items}',
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<h4>Приложенные документы</h4>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->attachedFiles,
        'modelClass' => AttachedFile::class,
        'pagination' => false,
    ]),
    'columns' => [
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{download}',
            'buttons' => [
                'download' => function ($url, $model, $key) use ($document) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-download']),
                        ['/edm/vtb-documents/download-attachment', 'id' => $document->id, 'fieldId' => 'DOCATTACHMENT', 'index' => $key],
                        [
                            'class' => 'delete-button',
                            'title' => Yii::t('app', 'Download'),
                            'data' => ['id' => $model->id],
                        ]
                    );
                }
            ],
            'contentOptions' => ['class' => 'text-right text-nowrap', 'width' => '35px']
        ]
    ],
    'layout' => '{items}',
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
    ],
]) ?>

<?php

$signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);

?>

<?= FastPrint::widget([
    'printUrl'     => Url::to(['print', 'id' => $document->id]),
    'printBtn'     => '#print-button',
    'documentId'   => $document->id,
    'documentType' => $document->type,
]) ?>

<?= TransportInfoModal::widget(['document' => $document]) ?>

<style>
    .detail-view td {
        width: 50%;
    }
    .detail-view, .grid-view {
        margin-bottom: 40px;
    }
    h5 {
        font-size: 17px;
    }
    .action-buttons-block {
        margin-bottom: 1em;
    }
    .action-buttons-block .btn {
        margin-right: 10px;
    }
</style>
