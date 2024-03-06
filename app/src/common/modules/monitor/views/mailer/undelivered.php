<?php

use common\helpers\NumericHelper;
use common\helpers\Html;
use yii\helpers\Url;
use common\modules\monitor\models\ExpiringCert;

$count = count($documentsData);
$suffix = NumericHelper::getPluralSuffix($count);

?>

<style type="text/css">
    .undelivered-table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    .undelivered-table td,
    .undelivered-table th {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
</style>

<h3><?= $terminalId; ?>: <?= \Yii::t('monitor/mailer', 'Notification'); ?></h3>

<p>
    <?= \Yii::t('monitor/mailer',
            'There {suffix, plural, =1{is 1 undelivered document} other{are {count} undelivered documents}} within last {deliveryDays} {suffix2, plural, =1{day} other{days}}',
            [
                'count' => $count, 'deliveryDays' => $deliveryDays,
                'suffix' => $suffix,
                'suffix2' => NumericHelper::getPluralSuffix($deliveryDays),
            ]); ?>
</p>

<table class="undelivered-table">
    <thead>
    <tr>
        <th>Номер</th>
        <th>Тип</th>
        <th>Дата создания</th>
        <th>Отправитель</th>
        <th>Получатель</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($documentsData as $document): ?>
            <tr>
                <td>
                    <?=Html::a($document['id'], Url::base() . "/document/view?id=" . $document['id']) ?>
                </td>
                <td><?=$document['typeGroup']?></td>
                <td><?=$document['dateCreate']?></td>
                <td><?=ExpiringCert::getTerminalName($document['sender'])?></td>
                <td><?=ExpiringCert::getTerminalName($document['receiver'])?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>