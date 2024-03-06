<?php

use addons\edm\models\Statement\StatementType;
use addons\finzip\models\FinZipType;
use addons\swiftfin\models\SwiftFinDocumentExt;
use common\base\BaseBlock;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\cyberxml\CyberXmlDocument;
use common\models\User;
use common\modules\certManager\models\Cert;
use common\modules\transport\models\CFTStatusReportType;
use common\widgets\documents\DeleteDocumentButton;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\TransportInfo\TransportInfoButton;
use common\widgets\TransportInfo\TransportInfoModal;
use kartik\form\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

Url::remember(Url::to(), 'edit');

/** @var View $this */

$isAdmin = in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);
$serviceId = $this->context->module instanceof BaseBlock ? $this->context->module->getServiceId() : null;

// Для edm-выписки уникальный заголовок
if ($model->type == StatementType::TYPE) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    $this->title = Yii::t('edm', 'Statement for {account} from {periodStart} to {periodEnd}', [
        'account' => $typeModel->statementAccountNumber,
        'periodStart' => $typeModel->statementPeriodStart,
        'periodEnd' => $typeModel->statementPeriodEnd
    ]);
}

$processingStatus = [
    Document::STATUS_CREATING,
    Document::STATUS_AUTOSIGNING,
    Document::STATUS_ANALYZING,
    Document::STATUS_ENCRYPTING,
    Document::STATUS_DECRYPTING,
    Document::STATUS_DELIVERING,
    Document::STATUS_DOWNLOADING,
    Document::STATUS_FORDOWNLOADING,
    Document::STATUS_FORPROCESSING,
    Document::STATUS_FORSENDING,
    Document::STATUS_FORUPLOADING,
    Document::STATUS_FOR_MAIN_AUTOSIGNING,
    Document::STATUS_PENDING,
    Document::STATUS_REQUESTING,
    Document::STATUS_SENDING,
    Document::STATUS_SIGNING,
    Document::STATUS_UPLOADING,
    Document::STATUS_CORRECTION,
];

$processingExtStatus = [
    SwiftFinDocumentExt::STATUS_INAUTHORIZATION,
];

$isVolatile = in_array($model->status, $processingStatus)
        || ($model->status == Document::STATUS_SERVICE_PROCESSING && isset($model->extModel->extStatus)
            && in_array($model->extModel->extStatus, $processingExtStatus));

// Рисуем общие данные документа

if (!isset($backUrl) || !is_array($backUrl)) {
    $backUrl = ['index'];
}
if (!isset($urlParams) || !is_array($urlParams)) {
    $urlParams = [];
}

$from = Yii::$app->request->get('from', false);
if ($from === 'controller-verification') {
    $backUrl = ['controller-verification'];
}

$items = [
	[
		'label' => Yii::t('doc', 'View'),
		'url' => Url::to(array_merge($urlParams, ['view', 'id' => $model->id, 'mode' => 'source'])),
		'active' => ($mode === 'source' || empty($mode))
	],
];

// Check admin role

if ($isAdmin) {

    if ($model->type == FinZipType::TYPE) {
        $items = [];
        $dataView = '_events';
    }

    $itemsAdmin = [
        [
            'label' => Yii::t('app/message', 'Events'),
            'url' => Url::to(array_merge($urlParams, ['view', 'id' => $model->id, 'mode' => 'events'])),
            'active' => ($mode === 'events' || ($model->type == FinZipType::TYPE && empty($mode))),
        ],
        [
            'label' => Yii::t('doc', 'Referencing documents') . ' (' . $referencingDataProvider->totalCount . ')',
            'url' => Url::to(array_merge($urlParams, ['view', 'id' => $model->id, 'mode' => 'references'])),
            'active' => $mode === 'references'
        ],
        [
            'label' => Yii::t('doc', 'Container'),
            'url' => Url::to(array_merge($urlParams, ['view', 'id' => $model->id, 'mode' => 'container'])),
            'active' => $mode === 'container'
        ],
    ];

    $items = ArrayHelper::merge($items, $itemsAdmin);
}
?>

<div class="panel-body" style="padding-top: 5px; padding-bottom: 5px;">

<div class="pull-right">
    <?= TransportInfoButton::widget() ?>
</div>

<?php
	echo Html::a(
        Yii::t('app', 'Back'),
        $backUrl,
        ['class' => 'btn btn-default btn-back-button']
    ) . '&nbsp;';

    $userCanDeleteDocuments = Yii::$app->user->can(
        DocumentPermission::DELETE,
        [
            'serviceId' => $serviceId,
            'document' => $model,
        ]
    );
    if ($userCanDeleteDocuments && $model->isDeletable()) {
        echo DeleteDocumentButton::widget(['documentId' => $model->id]);
    }
?>

<?php if (isset($actionView) && $actionView == 'viewStatement') {?>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=Yii::t('app', 'Print')?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-left">
            <li><?=Html::a(Yii::t('edm', 'Statement'),
                    Url::toRoute(['/edm/documents/print', 'id' => $model->id]), ['target' => '_blank', 'class' => 'print-statement'])?>
            </li>
            <li><?=Html::a(Yii::t('edm', 'All transactions'),
                    Url::toRoute(['/edm/documents/print-all', 'id' => $model->id]), ['target' => '_blank', 'class' => 'print-statement-all-transactions'])?>
            </li>
        </ul>
    </div>

    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=Yii::t('edm', 'Export')?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-left">
            <li><?=Html::a(Yii::t('edm', 'As {format}', ['format' => 'Excel']),
                    Url::toRoute(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => 'excel']), ['target' => '_blank'])?>
            </li>
            <li><?=Html::a(Yii::t('edm', 'As {format}', ['format' => '1C']),
                    Url::toRoute(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => '1c']), ['target' => '_blank'])?>
            </li>
            <li><?=Html::a(Yii::t('edm', 'As {format}', ['format' => '1C (операции по дебету)']),
                    Url::toRoute(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => '1c', 'mode' => 'debit']),
                    ['target' => '_blank'])?>
            </li>
            <li><?=Html::a(Yii::t('edm', 'As {format}', ['format' => '1C (операции по кредиту)']),
                    Url::toRoute(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => '1c', 'mode' => 'credit']),
                    ['target' => '_blank'])?>
            </li>
            <li>
                <?= Html::a(
                    Yii::t('edm', 'As {format}', ['format' => 'PDF (выписка)']),
                    Url::toRoute(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => 'pdf', 'mode' => 'summary']),
                    ['target' => '_blank']
                ) ?>
            </li>
            <li>
                <?= Html::a(
                    Yii::t('edm', 'As {format}', ['format' => 'PDF (операции)']),
                    Url::to(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => 'pdf', 'mode' => 'orders']),
                    ['target' => '_blank']
                ) ?>
            </li>
            <li>
                <?= Html::a(
                    Yii::t('edm', 'As {format}', ['format' => 'PDF (выписка и операции)']),
                    Url::to(['/edm/export/export-statement', 'id' => $model->id, 'exportType' => 'pdf']),
                    ['target' => '_blank']
                ) ?>
            </li>
        </ul>
    </div>
<?php } ?>

<?php
    // Кнопка Подписантов для вывода модального окна
    // Для админа и зашифрованных документов не отображать
//    if ($isAdmin && $model->isEncrypted) {
//        $showSignatories = false;
//    } else {
//        $showSignatories = true;
//    }
//    if ($showSignatories) {
//        echo '&nbsp;' . Html::a(
//            Yii::t('app', 'Signatories'),
//            '#',
//            [
//                'class' => 'btn btn-default btn-signers-button',
//                'data' => [
//                    'document-id' => $model->id
//                ]
//            ]
//        );
//    }
?>

<?php
    if (Yii::$app->user->can('documentControllerVerification')
        && $model->status === Document::STATUS_FOR_CONTROLLER_VERIFICATION
        && !is_null($autobot)) {
                ActiveForm::begin([
                    'action' => ['verify'],
                    'method' => 'post',
                    'options' => [
                        'style' => 'display: inline;'
                    ]
                ]);
                echo Html::hiddenInput('postDocIds[]', $model->id);
                echo Html::hiddenInput('action', 'accept');
                echo Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) . '&nbsp;';

                ActiveForm::end();

                ActiveForm::begin([
                    'action' => ['verify'],
                    'method' => 'post',
                    'options' => [
                        'style' => 'display: inline;'
                    ]
                ]);
                echo Html::hiddenInput('postDocIds[]', $model->id);
                echo Html::hiddenInput('action', 'reject');
                echo Html::submitButton(Yii::t('app', 'Do not send'), ['class' => 'btn btn-danger']);

                ActiveForm::end();
    }

    $isVerifiable = Yii::$app->user->identity->role !== User::ROLE_ADMIN
        && in_array($model->status, Document::getUserVerifiableStatus());

	if ($model->isSignableByUserLevel($serviceId)) {
        // Проверяем на наличие параметра адреса переадресации после подписания
        $redirectUrl = Yii::$app->request->get('redirectUrl');
        echo SignDocumentsButton::widget([
            'buttonText' => Yii::t('app/message', 'Signing'),
            'documentsIds' => [$model->id],
            'successRedirectUrl' => $redirectUrl
        ]);
	} else if ($isVerifiable) {
		echo ' ' . Html::a(Yii::t('app/message', 'User verify'),
			['/swiftfin/documents/user-verify', 'id' => $model->id],
			['class' => 'btn btn-success']);
	} else if ($model->isSendable()) {
		echo ' ' . Html::a(Yii::t('app/message', 'Send'),
			['/documents/send', 'id' => $model->id], ['class' => 'btn btn-primary']);
	}

    if ($model->isResendable() && Yii::$app->user->identity->role == User::ROLE_ADMIN) {
		echo ' ' . Html::a(Yii::t('app/message', 'Resend'),
			['/document/resend', 'id' => $model->id], ['class' => 'btn btn-primary']);

    }

	if (isset($customModuleActionView) && !empty($customModuleActionView)) {
        echo $this->render($customModuleActionView, ['model' => $model]);
	}

?>
</div>

<?php
// Если пункты доп. меню содержат только 1 элемент, то вообще их не отображаем
if (count($items) > 1):
?>

<div class="panel-body" style="padding-bottom: 5px">
	<?=
	Nav::widget([
		'activateItems' => true,
		'options' => [
			'class' => 'nav nav-pills',
		],
		'items' => $items,
	])
	?>
</div>

<?php endif ?>

<div class="panel-body">
    <?php
    $viewName = '_defaultView';
    switch ($mode) {
        case 'events':
            $viewName = '_events';

            break;
        case 'references':
            $viewName = '_references';

            break;
        case 'container':
            $viewName = '_container';

            break;
        default:
            if (isset($dataView)) {
                $viewName = $dataView;
            }
    }

    // Рендерим выбранное отображение
    echo $this->render(
        $viewName,
        [
            'model' => $model,
            'num' => isset($num) ? $num : null,
            'referencingDataProvider' => $referencingDataProvider,
            'commandDataProvider' => isset($commandDataProvider) ? $commandDataProvider : null
        ]
    );
    // Show signatures when not(admin and encrypted) and when dataview is main
    if (!($isAdmin && $model->isEncrypted)
        && ($viewName == $dataView || $viewName == '_defaultView')
    ) {
        //$mask = isset($showSignaturesMask) ? $showSignaturesMask : Document::SIGNATURES_TYPEMODEL;
        $mask = Document::SIGNATURES_ALL;
        echo $this->render('@common/views/document/_signatures', [
            'signatures' => $model->getSignatures($mask, Cert::ROLE_SIGNER)
        ]);
    }

    if ($model->hasErrors()) {
        echo '<br/><div class="alert alert-danger">';
        foreach($model->getErrors() as $error) {
            echo implode(', ', $error) . '<br/>';
        }
        echo '</div>';
    }

?>
</div>

<?php if ($isVolatile) : ?>
	<script type="text/javascript">
		setTimeout(function () {
			window.location.reload(1);
            var loc = window.location.toString();
		}, 5000);
	</script>
<?php endif ?>

<?php

//$this->registerCss('
//    #signersModal .modal-dialog {
//        width: 700px;
//    }
//');


//$request = Url::toRoute('/document/get-signers-info');
//
//$script = <<< JS
//    // Вызов всплывающего окна с информацией о подписантах
//    $('.btn-signers-button').on('click', function(e) {
//        e.preventDefault();
//
//        var documentId = $(this).data('document-id');
//
//        // Получение информации о подписантах
//        $.ajax({
//            url: '$request',
//            type: 'get',
//            data: {documentId: documentId},
//            success: function(answer){
//                // Загрузка даннных
//                $('#signersModal .modal-body').html(answer);
//
//                // Отображение окна
//                $('#signersModal').modal('show');
//            }
//        });
//    });
//JS;
//
//$this->registerJs($script, View::POS_READY);

// Форма с модальным окном вывода списка подписантов
//$header = '<h4 class="modal-title" id="myModalLabel">' . Yii::t('app', 'Signatories') . '</h4>';
//
//$modal = Modal::begin([
//    'id' => 'signersModal',
//    'header' => $header,
//    'footer' => null,
//]);
//$modal::end();

$errorDescription = null;

if ($model->status == Document::STATUS_REJECTED) {
    $uuid = $model->direction == Document::DIRECTION_OUT ? $model->uuid : $model->uuidRemote;
    if ($uuid) {

    	$statusReport = Document::find()
            ->where(['uuidReference' => $uuid])
            ->andWhere(['type' => CFTStatusReportType::TYPE])
            ->one();

        if ($statusReport) {
            $typeModel = CyberXmlDocument::getTypeModel($statusReport->actualStoredFileId);
            $errorDescription = $typeModel->errorDescription;
        }
    }
}

echo TransportInfoModal::widget(['document' => $model, 'isVolatile' => $isVolatile, 'errorDescription' => $errorDescription]); ?>
