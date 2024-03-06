<?php

use yii\helpers\Html;

/** @var \addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm $cancellationForm */
/** @var bool $userCanCancelDocument */

if ($userCanCancelDocument) {
    echo Html::a(
        Yii::t('edm', 'Call off the document'),
        '#',
        [
            'id'    => 'show-cancel-document-modal-button',
            'class' => 'btn btn-danger',
            'data'  => ['toggle' => 'modal', 'target' => '#cancel-document-modal']
        ]
    );
    echo $this->render('_cancelVtbDocumentModal', ['model' => $cancellationForm]);
}
