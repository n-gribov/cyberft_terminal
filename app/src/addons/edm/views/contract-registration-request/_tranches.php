<?php
/**
 * Вывод таблицы с информацией о траншах по документу "Паспорт сделки" типа "Кредитный договор"
 * @var Array $childObjectData
 * @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche $tranche
 */
use common\helpers\Html;
?>
<table class="table">
    <tr>
        <th><?=Yii::t('edm', "Tranche's amount")?></th>
        <th><?=Yii::t('edm', "Tranche's term code")?></th>
        <th><?=Yii::t('edm', 'The expected date of tranche')?></th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($childObjectData as $uuid => $tranche) : ?>
        <tr>
            <td><?=$tranche->amount?></td>
            <td><?=$tranche->termCode?></td>
            <td><?=$tranche->date?></td>
            <td><?=Html::a(Yii::t('yii', 'Update'), '#', [
                'class' => 'update-tranche',
                'data' => [
                    'uuid' => $uuid,
                    'title' => Yii::t('edm', 'Tranche')
                ]
            ])?></td>
            <td><?=Html::a(Yii::t('app', 'Delete'), '#', [
                'class' => 'delete-tranche',
                'data' => [
                    'uuid' => $uuid
                ]
            ])?></td>
        </tr>
    <?php endforeach ?>
</table>