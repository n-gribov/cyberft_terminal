<?php

use addons\edm\EdmModule;
use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\vtb\VTBHelper;
use common\models\listitem\AttachedFile;
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
/** @var ContractUnregistrationRequestForm $model */
/** @var Document $document */

$this->title = Yii::t('edm', 'Contract unregistration request');
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
            'documentTypeGroup' => EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
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
    } else if ($isSignable) {
        $buttonText = $document->signaturesCount == $document->signaturesRequired - 1
            ? Yii::t('document', 'Sign and send')
            : Yii::t('edm', 'Sign');

        echo SignDocumentsButton::widget([
            'buttonText' => $buttonText,
            'documentsIds' => [$document->id],
        ]);
    } else if ($userCanCreateDocuments && $isCancellableVTBDocument) {
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
<?php
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'documentNumber',
        'documentDate',
        [
            'attribute' => 'organization.name',
            'label' => Yii::t('edm', 'Company Name'),
        ],
        [
            'attribute' => 'organization.inn',
            'label' => Yii::t('edm', 'INN'),
        ],
        'contactPerson',
        'contactPhone',
        [
            'attribute' => 'receiverBank.name',
            'label' => Yii::t('edm', 'Authorized bank'),
        ]
    ]
]);
?>
<h4><?= Yii::t('edm', 'Contracts (loan agreements)') ?></h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->contracts,
        'modelClass' => ContractUnregistrationRequestForm\Contract::class,
        'pagination' => false,
    ]),
    'columns' => [
        'uniqueContractNumber',
        [
            'attribute' => 'uniqueContractNumberDate',
            'label' => Yii::t('edm', 'Date'),
        ],
        'unregistrationGroundName',
        'unregistrationGroundCode',
    ],
    'layout' => '{items}',
]);
?>
<h4>Приложенные документы</h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
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
]);

echo FastPrint::widget([
    'printUrl'     => Url::to(['print', 'id' => $document->id]),
    'printBtn'     => '#print-button',
    'documentId'   => $document->id,
    'documentType' => $document->type,
]);

echo TransportInfoModal::widget(['document' => $document]);
?>
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