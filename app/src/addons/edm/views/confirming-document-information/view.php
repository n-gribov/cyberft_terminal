<?php

use common\widgets\TransportInfo\TransportInfoModal;
use yii\helpers\Url;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt $model */
/** @var \common\document\Document $document */
/** @var \common\models\listitem\AttachedFile[][] $attachedFiles */
/** @var array $signatures */

$this->title = Yii::t('app/menu', 'Confirming document information') . ' #' . $model->id;

// Шаблон отображения управляющих кнопок
echo $this->render('@addons/edm/views/documents/_fccViewContent', [
    'model'              => $model,
    'document'           => $document,
    'type'               => 'cdi',
    'backUrl'            => '/edm/documents/foreign-currency-control-index?tabMode=tabCDI',
    'signingRedirectUrl' => '/edm/documents/foreign-currency-control-index?tabMode=tabCDI',
    'rejectSignUrl'      => '/edm/documents/foreign-currency-control/reject-signing',
    'printUrl'           => '/edm/confirming-document-information/print',
    'updateUrl'          => '/edm/confirming-document-information/update',
    'deleteUrl'          => '/edm/confirming-document-information/delete',
    'sendUrl'            => '/edm/confirming-document-information/send',
    'afterSignUrl'       => Url::to(['/edm/documents/foreign-currency-control-index?tabMode=tabCDI']),
    'exportExcelUrl'     => '/edm/export/export-fcc',
    'beforeSigningUrl'   => Url::to(['/edm/confirming-document-information/before-signing', 'id' => $document->id]),
]);

echo $this->render('_view', compact('model', 'attachedFiles', 'signatures'));

echo TransportInfoModal::widget(['document' => $document, 'isVolatile' => false]);
