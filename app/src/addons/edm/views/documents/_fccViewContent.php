<?php

use addons\edm\EdmModule;
use common\document\DocumentPermission;
use common\helpers\Html;
use common\document\Document;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Url;
use yii\web\View;

// Шаблон для отображания управляющих кнопок и подключения нужны скриптов в документах валютного контроля

/** @var Document $document */
/** @var string $type */
/** @var string $exportExcelUrl */
/** @var string $updateUrl */
/** @var string $deleteUrl */
/** @var string $sendUrl */
?>
<div class="row margin-bottom-15">
    <div class="col-md-12">

        <div class="pull-left">
            <?=Html::a('Назад', Url::to([$backUrl]), ['class' => 'btn btn-default']);?>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=Yii::t('app', 'Actions')?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-left">
                    <li>
                        <?=Html::a(Yii::t('app', 'Print'), Url::to([$printUrl, 'id' => $document->id]), ['class' => 'print-fcoi'])?>
                    </li>
                    <li>
                        <?= Html::a(
                            Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                            Url::to([$exportExcelUrl, 'id' => $document->id, 'fccType' => $type]),
                            ['target' => '_blank']
                        ) ?>
                    </li>
                </ul>
            </div>

        </div>
        <div class="pull-right">

            <?php
                // Управляющие кнопки документа
                if ($document->isModifiable()) {
                    // Если документ еще не отправлен

                    $userCan = function (string $permissionCode) use ($document) {
                        return Yii::$app->user->can(
                            $permissionCode,
                            [
                                'serviceId' => EdmModule::SERVICE_ID,
                                'document' => $document,
                            ]
                        );
                    };

                    $userCanCreateDocuments = $userCan(DocumentPermission::CREATE);
                    $userCanDeleteDocuments = $userCan(DocumentPermission::DELETE);
                    $userAdmin = Yii::$app->user->can('admin');

                    if ($userCanCreateDocuments) {
                        echo Html::a('Редактировать', Url::to([$updateUrl, 'id' => $document->id]), ['class' => 'btn btn-primary']);
                        echo '&nbsp;';
                    }

                    if ($userCanDeleteDocuments || $userAdmin) {
                        echo Html::a('Удалить', Url::to([$deleteUrl, 'id' => $document->id]), [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                        echo '&nbsp;';
                    }

                    if (!in_array($document->status, [
                            Document::STATUS_FORSIGNING,
                            Document::STATUS_SIGNING_REJECTED,
                        ]) && $userCanCreateDocuments) {
                        echo Html::a(
                            Yii::t('app', 'Send'),
                            Url::to([$sendUrl, 'id' => $document->id]),
                            ['class' => 'btn btn-success']
                        );
                    } else if ($document->isSignableByUserLevel(EdmModule::SERVICE_ID)) {
                        echo Html::a(
                            Yii::t('document', 'Sign and send'), '#',
                            [
                                'class' => 'btn btn-success',
                                'id' => 'btn-before-sign',
                                'data' => [
                                    'url' => $beforeSigningUrl
                                ]
                            ]
                        );

                        $successRedirectUrl = isset($afterSignUrl) ? $afterSignUrl : null;
                        echo SignDocumentsButton::widget([
                            'buttonText' => Yii::t('document', 'Sign and send'),
                            'documentsIds' => [$document->id],
                            'successRedirectUrl' => $successRedirectUrl
                        ]);
                        echo ' ' . $this->render('_rejectSigning', ['id' => $document->id, 'url' => $rejectSignUrl]);
                    }
                }
                echo \common\widgets\TransportInfo\TransportInfoButton::widget();
            ?>
        </div>
    </div>
</div>

<?php

// Печать
$printBtn = '.print-fcoi';

echo FastPrint::widget([
    'printUrl' => Url::to([$printUrl, 'id' => $document->id]),
    'printBtn' => $printBtn,
    'documentId' => $document->id
]);

$this->registerCss('
    .margin-bottom-15 {
        margin-bottom: 15px;
    }

    #signersModal .modal-dialog {
        width: 700px;
    }

    #sign-documents-button {
        display: none;
    }
');

$script = <<< JS
    $('#btn-before-sign').on('click', function(e) {
        e.preventDefault();

        var url = $(this).data('url');

        $(this).hide();

        $.post(url).done(function() {
            $('#sign-documents-button').show();
            $('#sign-documents-button').click();
        });
    });
JS;

// Если требуется подписать документ, инициализируем отложенный редирект на страницу подписания
$get = Yii::$app->request->get();

if (Yii::$app->request->get('triggerSigning')) {
    $this->registerJs("$('#sign-documents-button').trigger('click');", View::POS_READY);
}

$this->registerJs($script, yii\web\View::POS_READY);

?>