<?php

/**
 * Секция 8 - Справочная информация о кредитном договоре
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */
/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule $payment */
/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident $nonresident */

// Виды оснований заполнения графика платежей
$reasons = [
    'loan' => [
        'number' => '9.1.1',
        'label' => 'Сведения из кредитного договора'
    ],
    'analytics' => [
        'number' => '9.1.2',
        'label' => 'Оценочные данные'
    ],
];

?>

<div class="section section-9">
    <div class="section-header">9. Справочная информация о кредитном договоре</div>
    <div class="section-subheader section-subheader-9-1">9.1. Основания заполнения пункта 9.2</div>
    <div class="section-9-1">
        <?php foreach($reasons as $id => $reason) : ?>
            <div class="section-9-1-item">
                <div class="section-9-1-item-number"><?=$reason['number']?></div>
                <div class="section-9-1-item-title"><?=$reason['label']?></div>
                <div class="section-9-1-item-field"><?=$model->reasonFillPaymentsSchedule == $id ? '+' : ""?></div>
            </div>
        <?php endforeach ?>
    </div>
    <div class="section-9-2">
        <div class="section-subheader">9.2. Описание графика платежей по возврату основного долга и процентных платежей</div>
        <table class="crr-loan-table">
            <tr>
                <td rowspan="3">№<br> п/п</td>
                <td rowspan="3">Код<br> валюты<br> кредитного<br> договора</td>
                <td colspan="4">Суммы платежей по датам их осуществления,<br>
                    в единицах валюты кредитного договора</td>
                <td rowspan="3">Описание<br> особых условий</td>
            </tr>
            <tr>
                <td colspan="2">по погашению основного долга</td>
                <td colspan="2">в счет процентных платежей</td>
            </tr>
            <tr>
                <td>дата</td>
                <td>сумма</td>
                <td>дата</td>
                <td>сумма</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
            </tr>
            <?php foreach($model->paymentScheduleItems as $id => $payment) : ?>
                <td><?=++$id?></td>
                <td><?=$model->currency->name?></td>
                <td><?=$payment->mainDeptDate?></td>
                <td><?=$payment->mainDeptAmount?></td>
                <td><?=$payment->interestPaymentsDate?></td>
                <td><?=$payment->interestPaymentsAmount?></td>
                <td><?=$payment->comment?></td>
            <?php endforeach ?>
        </table>
    </div>
    <div class="section-9-3">
        <div class="section-subheader">9.3. Отметка о наличии отношений прямого инвестирования</div>
        <div class="section-9-3-field"><?=$model->directInvestment ? '+' : ''?></div>
    </div>
    <div class="section-9-4">
        <div class="section-subheader">9.4. Сумма залогового или другого обеспечения</div>
        <div class="section-9-4-field"><?=$model->amountCollateral?></div>
    </div>
    <div class="section-9-5">
        <div class="section-subheader section-header-format"><div class="number">9.5.</div>Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе</div>
        <table class="crr-loan-table">
            <tr>
                <td>№<br>п/п</td>
                <td>Наименование нерезидента</td>
                <td>Код страны<br> места<br> нахождения<br> нерезидента</td>
                <td>Предоставляемая сумма<br> денежных средств,<br>в единицах валюты<br> кредитного договора</td>
                <td>Доля в общей<br>сумме кредита<br> (займа), %</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
            </tr>
            <?php foreach($model->nonresidentsCreditItems as $id => $nonresident) : ?>
                <td><?=++$id?></td>
                <td><?=$nonresident->name?></td>
                <td><?=$nonresident->numericCountryCode?></td>
                <td><?=$nonresident->amount?></td>
                <td><?=$nonresident->percent?></td>
            <?php endforeach ?>
        </table>
    </div>
</div>
