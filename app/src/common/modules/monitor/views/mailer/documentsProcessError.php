<?php
    use common\helpers\NumericHelper;
    use common\document\Document;
?>

<style type="text/css">

    .document-process-error-table {
        border-spacing: 0;
        margin: 10px 0px;
    }

    .document-process-error-table td,
    .document-process-error-table th {
        border: 1px solid black;
        padding: 5px;
    }

</style>

<h3><?= $terminalId; ?>: <?= \Yii::t('monitor/mailer', 'Notification'); ?></h3>

<?= \Yii::t('monitor/mailer',
        'There {suffix, plural, =1{is 1 failed document} other{are {count} failed documents}}',
        ['count' => $count, 'suffix' => NumericHelper::getPluralSuffix($count)])?>

<?php if (!empty($events)) { ?>

    <table class="document-process-error-table">

        <thead>
            <th>#</th>
            <th>Тип документа</th>
            <th>Предыдущий статус</th>
            <th>Новый статус</th>
        </thead>

        <?php foreach($events as $item) {?>

            <?php
                $document = Document::findOne($item->entityId);
                $event = $item->getEvent();
            ?>

            <tr>
                <td>
                    <?=$document->id?>
                </td>
                <td>
                    <?=$document->type?>
                </td>
                <td>
                    <?=$event->previousStatus?>
                </td>
                <td>
                    <?=$event->status?>
                </td>
            </tr>

        <?php } ?>

    </table>

<?php } ?>

