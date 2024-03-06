<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\vtb\VTBHelper;
use common\models\User;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\GridView;
use common\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var View $this */
/** @var PaymentRegisterDocumentExt $extModel */
/** @var Document $document */

$this->title = Yii::t('edm', 'View payment register #{id}', ['id' => $document->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('edm', 'Payment registers'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

$processingStatus = [
    Document::STATUS_CREATING,
    Document::STATUS_SENDING,
    Document::STATUS_SIGNING,
    Document::STATUS_SERVICE_PROCESSING,
];

$isVolatile = in_array($document->status, $processingStatus);

// Проверка на наличие параметра страницы, откуда пришел пользователь
$redirectUrl = Yii::$app->request->get('redirectUrl');

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
    ]
);
?>
<div style="margin-bottom: 10px">
<?php
    echo Html::a(
        Yii::t('app', 'Back'),
        ['index'],
        ['class' => 'btn btn-default', 'style' => 'margin-right: 5px']
    );
    if ($document->isSignableByUserLevel(EdmModule::SERVICE_ID)) {
    echo SignDocumentsButton::widget([
        'buttonText' => Yii::t('app/message', 'Signing'),
        'documentsIds' => [$document->id],
        'successRedirectUrl' => $redirectUrl,
    ]);
    // Вывести страницу отказа от подписания
    echo ' ' . $this->render('_rejectSigning', ['id' => $document->id]);
} else if ($document->isSendable() && Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
    echo Html::a(
        Yii::t('app/message', 'Send'),
        ['send', 'id' => $document->id],
        ['class' => 'btn btn-primary'],
        'ic-send'
    );
} else if (VTBHelper::isVTBDocument($document) && VTBHelper::isCancellableDocument($document) && $userCanCreateDocuments) {
    echo Html::a(Yii::t('edm', 'Call off the document'),
        ['/edm/vtb-documents/view', 'id' => $document->id, 'triggerCancellation' => 1],
        ['class' => 'btn btn-danger']
    );
}
?>
<div class="row">
    <div class="col-xs-6" style="padding-bottom: 15px;">
    <?= $this->blocks['pageActions'] ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $extModel,
            'template' => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'document.dateCreate',
                'payerName',
                'accountNumber',
                'payerBankName',
                [
                    'attribute' => 'sum',
                    'format' => 'html',
                    'value' => Yii::$app->formatter->asDecimal($extModel->sum, 2)
                ],
                'currency',
            ],
        ]) ?>
    </div>
    <div class="col-xs-4">
    <?php
        $detailAttributes = [
            'count',
            'document.signaturesRequired',
            'document.signaturesCount',
            [
                'attribute' => 'document.status',
                'format' => 'html',
                'value' => ($isVolatile ? '<i class="fa fa-spinner fa-spin"></i> ' : '')
                    . $document->getStatusLabel()
            ],
        ];

        // Поля, связанные с бизнес-статусом отображаем только для исходящих документов
        if ($document->direction == Document::DIRECTION_OUT) {
            $detailAttributes[] = [
                'attribute' => 'businessStatusTranslation',
            ];

            $detailAttributes[] = [
                'attribute' => 'businessStatusDescription'
            ];

            if ($extModel->businessStatusComment) {
                $detailAttributes[] = [
                    'attribute' => 'businessStatusComment'
                ];
            }
        }
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $extModel,
            'template' => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => $detailAttributes,
            'formatter' => [
                'class' => '\yii\i18n\Formatter',
                'nullDisplay' => ''
            ]
        ]);
        ?>
    </div>
</div>
<?php if ((Document::STATUS_SIGNING_REJECTED == $document->status) && !empty($statusEvent)) : ?>
    <div class="alert alert-warning">
        <p><?=$statusEvent->label?></p>
        <?= Html::encode($statusEvent->reason) ?>
    </div>
<?php endif ?>
<?php
// Создать таблицу для вывода
$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'rowConfig' => [
        'attrColor' => 'businessStatus',
        'map'   => [
            'RJCT' => 'red',
        ]
    ],
    'actions'   => [
        'template' => '{view}',
        'urlCreator' => function($action, $document, $key, $index ) {
            return Url::toRoute([
                '/edm/payment-register/payment-order-view',
                'id' => $document->id,
                'from' => 'viewPaymentRegister'
            ]);
        },
    ],
    'columns' => [
        [
            'attribute' => 'id',
            'textAlign' => 'right',
            'width' => 'narrow',
        ],
        [
            'attribute'  => 'number',
            'textAlign' => 'right',
            'width' => 'narrow',
        ],
        [
            'attribute' => 'date',
            'textAlign' => 'right',
            'nowrap' => true,
        ],
        [
            'attribute' => 'payerName',
        ],
        [
            'attribute' => 'beneficiaryName',
        ],
        [
            'attribute' => 'beneficiaryCheckingAccount',
            'width' => 'narrow',
        ],
        [
            'attribute' => 'sum',
            'value' => function ($model) {
                return Yii::$app->formatter->asDecimal($model->sum, 2);
            },
            'textAlign' => 'right',
            'options' => [
                'width' => '150px'
            ],
        ],
        [
            'attribute' => 'currency',
        ],
        [
            'attribute' => 'paymentPurpose',
            'options' => [
                'width' => '200px'
            ]
        ],
        [
            'attribute' => 'businessStatus',
            'value' => function($model) {
                return PaymentRegisterDocumentExt::translateBusinessStatus($model);
            }
        ],
    ],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
// Вывести блок подписей
echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
?>
<?php if ($isVolatile) : ?>
    <script type="text/javascript">
        setTimeout(function () {
                window.location.reload(1);
        }, 5000);
    </script>
<?php endif ?>
<?php
if (Yii::$app->request->get('triggerSigning')) {
    $this->registerJs("$('#sign-documents-button').trigger('click');", View::POS_READY);
}
