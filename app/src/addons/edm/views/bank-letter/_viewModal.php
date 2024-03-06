<?php

use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\BankLetter\BankLetterViewModel;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\widgets\documents\DeleteDocumentButton;
use common\widgets\documents\SignDocumentsButton;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var BankLetterSearch $document */
/** @var BankLetterViewModel $letter */

$status = DocumentHelper::getStatusLabel($document);
$statusValue = $status['label'];

$transportInfoAttributes = [
    [
        'attribute' => 'senderParticipant.name',
        'label' => Yii::t('document', 'Sender'),
    ],
    [
        'attribute' => 'receiverParticipant.name',
        'label' => Yii::t('document', 'Receiver'),
    ],
    [
        'attribute' => 'status',
        'value' => $statusValue
    ],
    'uuid',
    'origin',
    'directionLabel',
    [
        'attribute' => 'signaturesRequired',
        'visible' => !is_null($document->signaturesRequired),
    ],
    [
        'attribute' => 'signaturesCount',
        'visible' => !is_null($document->signaturesCount),
    ],
    'dateCreate',
    'dateUpdate'
];

if ($document->direction === Document::DIRECTION_OUT) {
    $transportInfoAttributes[] = [
        'label' => Yii::t('document','Execution status'),
        'value' => $letter->businessStatusLabel,
    ];
    $transportInfoAttributes[] = [
        'label' => Yii::t('document','Status description'),
        'value' => $letter->businessStatusDescription,
    ];
}

?>
<div id="view-modal" class="fade modal" role="dialog" data-keyboard="false">
    <div class="modal-dialog " style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <?= ($tp != 'IMPT') ? Yii::t('edm', 'Letter') : Yii::t('edm', 'Important Letter') ?>
                </h4>
            </div>
            <div class="modal-body">
                <a href="#" class="btn-transport-info">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    <?= Yii::t('document', 'Transport information') ?>
                </a>
                <div class="transport-info">
                    <?= DetailView::widget([
                        'model' => $document,
                        'attributes' => $transportInfoAttributes,
                    ]) ?>
                </div>
                <p>
                    <strong><?= Yii::t('edm', 'Date') ?>:</strong>
                    <?= Html::encode($letter->date) ?>
                </p>
                <p>
                    <strong><?= Yii::t('edm', 'From') ?>:</strong>
                    <?= Html::encode($letter->senderName) ?>
                </p>
                <p>
                    <strong><?= Yii::t('edm', 'Addressed to') ?>:</strong>
                    <?= Html::encode($letter->receiverName) ?>
                </p>
                <p style="word-wrap: break-word">
                    <strong><?= Yii::t('edm', 'Subject') ?>:</strong>
                    <?= Html::encode($letter->subject) ?>
                </p>
                <p>
                    <strong><?= Yii::t('edm', 'Request type') ?>:</strong>
                    <?= Html::encode($letter->messageType) ?>
                </p>

                <?php if (!empty($letter->attachedFiles)) : ?>
                    <p>
                        <strong><?= (count($letter->attachedFiles) > 1
                            ? Yii::t('edm', 'Attachments') . ':<br/>'
                            : Yii::t('edm', 'Attachment') . ':'
                        )?></strong>
                        <?php foreach($letter->attachedFiles as $attachedFile) :?>
                            <?= Html::a($attachedFile['fileName'], $attachedFile['url']) ?><br/>
                        <?php endforeach ?>
                    </p>
                <?php endif ?>

                <hr>
                <p style="word-wrap: break-word">
                    <?= Yii::$app->formatter->asNtext($letter->message) ?>
                </p>
            </div>
            <div class="modal-footer">
                <div>
                    <?php
                    if ($letter->canBeDeleted()) {
                        echo DeleteDocumentButton::widget(['documentId' => $document->id]);
                    }
                    if ($letter->canBeEdited()) {
                        echo Html::button(
                            Yii::t('app', 'Edit'),
                            [
                                'class' => 'btn btn-primary edit-letter-button',
                                'data' => [
                                    'id' => $document->id,
                                ],
                            ]
                        );
                    }
                    if ($letter->canBeSent()) {
                        echo Html::a(
                            Yii::t('app', 'Send'),
                            Url::to(['/edm/bank-letter/send', 'id' => $document->id]),
                            ['class' => 'btn btn-success']
                        );
                    } else if ($letter->canBeSigned()) {
                        $buttonText = $document->signaturesCount == $document->signaturesRequired - 1
                            ? Yii::t('document', 'Sign and send')
                            : Yii::t('edm', 'Sign');

                        echo SignDocumentsButton::widget([
                            'buttonText' => $buttonText,
                            'documentsIds' => [$document->id],
                            'alertsContainerSelector' => '#view-modal .modal-body',
                            'isInsideAjaxDocument' => true,
                        ]);
                    } else if ($letter->canBeCalledOff()) {
                        echo Html::a(Yii::t('edm', 'Call off the document'),
                            ['/edm/vtb-documents/view', 'id' => $document->id, 'triggerCancellation' => 1],
                            ['class' => 'btn btn-danger']
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
