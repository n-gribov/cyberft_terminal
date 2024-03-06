<?php

use common\helpers\NumericHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\monitor\models\ExpiringCert;

$count = count($documentsData);
$suffix = NumericHelper::getPluralSuffix($count);

?>

<style type="text/css">
    .for-signing-table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    .for-signing-table td,
    .for-signing-table th {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
</style>

<h3><?= $terminalId; ?>: <?= Yii::t('monitor/mailer', 'Notification'); ?></h3>

<?= Yii::t('monitor/mailer',
        'There {suffix, plural, =1{is 1 unsigned document} other{are {count} unsigned documents}}',
        ['count' => $count, 'suffix' => $suffix])?>
<br/><br/>

<table class="for-signing-table">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Тип</th>
            <th>Дата создания</th>
            <th>Отправитель</th>
            <th>Получатель</th>
            <th>Требуется подписей</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($documentsData as $document): ?>
            <tr>
                <td>
                    <?=Html::a($document['id'], Url::base() . "/document/view?id=" . $document['id']) ?>
                </td>
                <td><?=$document['type']?></td>
                <td><?=$document['date']?></td>
                <td><?=ExpiringCert::getTerminalName($document['sender'])?></td>
                <td><?=ExpiringCert::getTerminalName($document['receiver'])?></td>
                <td><?=$document['signaturesRequired']?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>