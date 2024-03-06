<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var \yii\web\View $this */
/** @var \common\widgets\documents\SignDocumentsButton $widget */

$prepareDocumentsIdsJsFunction = $widget->prepareDocumentsIds;
if (empty($prepareDocumentsIdsJsFunction)) {
    $documentsIdsJsArray = json_encode($widget->documentsIds);
    $prepareDocumentsIdsJsFunction = new JsExpression("function (signCallback) { signCallback($documentsIdsJsArray); }");
}

$documentSignerParams = [
    'fetchSignedDataUrl'                  => Url::to(['/documents-signing/get-signed-data']),
    'saveSignaturesUrl'                   => Url::to(['/documents-signing/save-signatures']),
    'getSigningStatusUrl'                 => Url::to(['/documents-signing/get-signing-status']),
    'commonSignatureErrorMessage'         => Yii::t('document', 'Document was not signed'),
    'commonMultipleSignatureErrorMessage' => Yii::t('document', 'Documents were not signed'),
    'cyberSignServiceSetupErrorMessage'   => '<p>Не установлен или не запущен сервис подписания CyberSignService.</p>'
        . '<p>Загрузить ПО CyberSignService: <a href="http://download.cyberft.ru/CyberSignService/CyberSignService.zip" target="_blank">http://download.cyberft.ru/CyberSignService/CyberSignService.zip</a></p>'
        . '<p>Инструкция по установке и настройке: <a href="http://download.cyberft.ru/CyberSignService/CyberSignService_manual.pdf" target="_blank">http://download.cyberft.ru/CyberSignService/CyberSignService_manual.pdf</a></p>'
        . '<p>В случае вопросов обращайтесь на <a href="mailto:support@cyberft.ru">support@cyberft.ru</a></p>',
    'cyberSignServiceVersionErrorMessage' => '<p>На компьютере установлена устаревшая версия сервиса подписания CyberSignService, что может привести к ошибкам в работе сервиса.</p>'
        . '<p>Для продолжения работы сервиса загрузите и установите новую версию ПО CyberSignService: <a href="http://download.cyberft.ru/CyberSignService/CyberSignService.zip" target="_blank">http://download.cyberft.ru/CyberSignService/CyberSignService.zip</a></p>'
        . '<p>В случае вопросов обращайтесь на <a href="mailto:support@cyberft.ru">support@cyberft.ru</a></p>',
    'useVersionCheck'                     => $widget->useVersionCheck,
    'requiredVersion'                     => $widget->requiredVersion,
    'successRedirectUrl'                  => $widget->successRedirectUrl,
    'entriesSelectionCacheKey'            => $widget->entriesSelectionCacheKey,
    'alertsContainerSelector'             => $widget->alertsContainerSelector,
    'prepareDocumentsIdsCallback'         => $prepareDocumentsIdsJsFunction->expression,
];

echo Html::button(
    $widget->buttonText,
    [
        'id'    => $widget->id,
        'class' => 'btn btn-success sign-documents-button',
        'data'  => [
            'loading-text' => "<i class='fa fa-spinner fa-spin'></i> {$widget->buttonText}",
            'params' => json_encode((object)$documentSignerParams)
        ]
    ]
);

$initializationJsCode = <<<JS
    $('body')
        .off('click', '.sign-documents-button:not(.disabled)')
        .on('click', '.sign-documents-button:not(.disabled)', function () {
            var signer = new DocumentsSigner($(this));
            signer.startSigning();
        });
JS;
$signerServiceJsUrl = 'https://localhost:45678/api.js';

if ($widget->isInsideAjaxDocument) {
    echo "<script>$initializationJsCode</script>";
    echo "<script src=\"$signerServiceJsUrl\"></script>";
} else {
    $this->registerJs($initializationJsCode);
    $this->registerJsFile($signerServiceJsUrl);
}

