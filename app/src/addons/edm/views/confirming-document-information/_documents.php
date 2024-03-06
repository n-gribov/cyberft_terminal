<?php

use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $childObjectData \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem */

?>

<h4><?= Yii::t('edm', 'Confirming documents') ?></h4>
<table class="table table-bordered">
    <tr>
        <th>Номер строки</th>
        <th>№ подтв. док.<br>Дата подтв. док</th>
        <th>Код вида подтверждающего документа</th>
        <th>Сумма в валюте документа<br>Сумма в валюте контракта</th>
        <th>Валюта документа<br>Валюта контракта</th>
        <th>Признак поставки</th>
        <th>Ожидаемый срок поставки</th>
        <th>Код страны грузоотправителя (грузополучателя)</th>
        <th>Приложенные документы</th>
        <th></th>
    </tr>
    <?php
    $i = 0;
    ?>
    <?php foreach ($childObjectData as $uuid => $document) : ?>
        <?php /** @var \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem $document */ ?>
        <tr class="documents-item">
            <td>
                <?= ++$i ?>
            </td>
            <td>
                <?= Html::encode($document->number ?: 'БН') ?>
                <br>
                <?= $document->date ?>
            </td>
            <td>
                <?= $document->code ?>
            </td>
            <td class="text-right">
                <?= Yii::$app->formatter->asDecimal($document->sumDocument, 2) ?>
                <br>
                <?= Yii::$app->formatter->asDecimal($document->sumContract, 2) ?>
            </td>
            <td>
                <?= $document->currencyDocumentTitle ?>
                <br>
                <?= $document->currencyContractTitle ?>
            </td>
            <td>
                <?= Html::encode($document->getTypeLabel()) ?>
            </td>
            <td>
                <?= $document->expectedDate ?>
            </td>
            <td>
                <?= $document->countryCode ?>
            </td>
            <td class="attachments-cell">
                <ul>
                    <?php foreach ($document->attachedFiles as $attachedFile) : ?>
                        <li>
                            <?= Html::a(
                                $attachedFile->name, [
                                    'download-temporary-attachment',
                                    'documentUuid' => $uuid,
                                    'attachmentUuid' => $attachedFile->id,
                                ]
                            ) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </td>
            <td class="action-column">
                <?php
                echo Html::a(
                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']),
                    '#',
                    [
                        'title' => Yii::t('app', 'Edit'),
                        'class' => 'update-document',
                        'data'  => ['uuid' => $uuid],
                    ]
                );
                echo '&nbsp;';
                echo Html::a(
                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']),
                    '#',
                    [
                        'title' => Yii::t('app', 'Delete'),
                        'class' => 'delete-document',
                        'data'  => ['uuid' => $uuid],
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php

$script = <<<JS
    // Удаление строки с документом
    $('.delete-document').on('click', function(e) {
        e.preventDefault();

        var uuid = $(this).data('uuid');

        $.ajax({
            url: '/edm/confirming-document-information/delete-document',
            data: "uuid=" + uuid,
            type: 'get',
            success: function(result) {
                // Отображение таблицы операций
                $('.documents').html(result);
                checkCreateButton();

                // Удаление кэша документа
                $.post('/wizard-cache/cdi', {DeleteDocument: uuid});
            }
        });
    });

    // Обновление строки с операцией
    $('.update-document').on('click', function(e) {
        e.preventDefault();

        var uuid = $(this).data('uuid');

        $.ajax({
            url: '/edm/confirming-document-information/update-document',
            data: "uuid=" + uuid,
            type: 'get',
            success: function(result) {
                $('#document-modal .modal-body').html(result);
                attachFileController.initialize();
                $('#document-modal').modal('show');
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCss('
    .documents-item .action-column {
        text-align: right;
        vertical-align: middle;
        white-space: nowrap;
    }
    .documents-item .attachments-cell ul {
        padding-left: 20px;
    }
    .documents-item .attachments-cell ul>li:not(:last-child) {
        margin-bottom: 10px;
    }
');

?>