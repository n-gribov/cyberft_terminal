<?php
/**
 * Вывод таблицы с графиком платежей по документу "Паспорт сделки"
 */

/**
 * @var Array $childObjectData
 * @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule $paymentSchedule
 */
use common\helpers\Html;
?>

<table class="table">
    <tr>
        <th colspan="2"><?=Yii::t('edm', 'Repayment of principal')?></th>
        <th colspan="2"><?=Yii::t('edm', 'On account of interest payments')?></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th><?=Yii::t('edm', "Date")?></th>
        <th><?=Yii::t('edm', "Amount")?></th>
        <th><?=Yii::t('edm', "Date")?></th>
        <th><?=Yii::t('edm', "Amount")?></th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($childObjectData as $uuid => $paymentSchedule): ?>
        <tr>
            <td><?=$paymentSchedule->mainDeptDate?></td>
            <td><?=$paymentSchedule->mainDeptAmount?></td>
            <td><?=$paymentSchedule->interestPaymentsDate?></td>
            <td><?=$paymentSchedule->interestPaymentsAmount?></td>
            <td><?=Html::a(Yii::t('yii', 'Update'), '#', [
                    'class' => 'update-payment-schedule',
                    'data' => [
                        'uuid' => $uuid,
                        'title' => Yii::t('edm', 'Nonresident')
                    ]
                ])?></td>
            <td><?=Html::a(Yii::t('app', 'Delete'), '#', [
                    'class' => 'delete-payment-schedule',
                    'data' => [
                        'uuid' => $uuid
                    ]
                ])?></td>
        </tr>
    <?php endforeach; ?>
</table>
