<?php
use common\helpers\NumericHelper;

/** @var string|null $participantName */
/** @var array $documents */

?>
<h3><?= $participantName ? "$participantName, " : '' ?><?= $terminalId ?>:</h3>
<?php $listStyle = count($documents) > 1 ? '' : 'style="list-style: none; padding-left: 0;"' ?>
<ul <?= $listStyle ?>>
    <?php foreach ($documents as $type => $count) : ?>
    <li>
        <?= Yii::t(
            'monitor/mailer',
            'Received {suffix, plural, =1{one document} other{{count} documents}} of {type}',
            [
                'type' => $type === 'finzip' ? 'свободного формата' : $type,
                'count' => $count,
                'suffix' => NumericHelper::getPluralSuffix($count)
            ]
        ) ?>.
    </li>
    <?php endforeach ?>
</ul>
