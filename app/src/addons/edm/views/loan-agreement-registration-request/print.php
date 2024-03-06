<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm $model */
/** @var \common\document\Document $document */
/** @var \addons\edm\models\VTBCredReg\VTBCredRegType $typeModel */
/** @var \DateTime $receiveDate */
/** @var \DateTime $acceptDate */
/** @var \DateTime $rejectDate */
/** @var string $rejectReason */

function formatCurrencyValue($value)
{
    return $value !== null || $value === ''
        ? number_format((float)$value, 2, '.', ' ')
        : null;
}

function renderUniqueLoanAgreementNumber($number, $class)
{
    $chars = str_split(str_pad($number, 18));
    ?>
    <table class="chars-table unique-number-table <?= $class ?>">
        <tr>
            <td><?= $chars[0] ?></td>
            <td><?= $chars[1] ?></td>
            <td><?= $chars[2] ?></td>
            <td><?= $chars[3] ?></td>
            <td><?= $chars[4] ?></td>
            <td><?= $chars[5] ?></td>
            <td><?= $chars[6] ?></td>
            <td><?= $chars[7] ?></td>
            <td>/</td>
            <td><?= $chars[8] ?></td>
            <td><?= $chars[9] ?></td>
            <td><?= $chars[10] ?></td>
            <td><?= $chars[11] ?></td>
            <td>/</td>
            <td><?= $chars[12] ?></td>
            <td><?= $chars[13] ?></td>
            <td><?= $chars[14] ?></td>
            <td><?= $chars[15] ?></td>
            <td>/</td>
            <td><?= $chars[16] ?></td>
            <td>/</td>
            <td><?= $chars[17] ?></td>
        </tr>
    </table>
    <?php
}

?>

<div class="bordered text-center">
    <?= Html::encode($model->receiverBank->name) ?>
</div>
<p class="small text-center">Наименование банка УК</p>

<h3 class="text-center">Заявление о постановке кредитного договора на учет</h3>

<div>
    <?php
    $loanAgreementUniqueNumber = implode(
        '',
        [
            str_pad($model->loanAgreementUniqueNumber1, 8, ' '),
            str_pad($model->loanAgreementUniqueNumber2, 4, ' '),
            str_pad($model->loanAgreementUniqueNumber3, 4, ' '),
            str_pad($model->loanAgreementUniqueNumber4, 1, ' '),
            str_pad($model->loanAgreementUniqueNumber5, 1, ' '),
        ]
    );
    renderUniqueLoanAgreementNumber($loanAgreementUniqueNumber, 'pull-right');
    ?>
    <strong>Уникальный номер<br>кредитного договора</strong>
</div>

<h4>1. Сведения о резиденте</h4>
<table class="full-width">
    <tr>
        <td colspan="2">1.1. Наименование</td>
        <td colspan="6" class="bordered"><?= Html::encode($model->organization->name) ?></td>
    </tr>
    <tr>
        <td>1.2. Адрес:</td>
        <td colspan="3">Субъект Российской Федерации</td>
        <td colspan="4" class="bordered"><?= Html::encode($model->organization->state) ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Район</td>
        <td colspan="4" class="bordered"><?= Html::encode($model->organization->district) ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Город</td>
        <td colspan="4" class="bordered"><?= Html::encode($model->organization->city) ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Населенный пункт</td>
        <td colspan="4" class="bordered"><?= Html::encode($model->organization->locality) ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Улица (проспект, переулок и т.д.)</td>
        <td colspan="4" class="bordered"><?= Html::encode($model->organization->street) ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2">Номер дома (владение)</td>
        <td class="bordered" style="min-width: 35px;"><?= Html::encode($model->organization->buildingNumber) ?></td>
        <td>Корпус (строение)</td>
        <td class="bordered" style="min-width: 35px;"><?= Html::encode($model->organization->building) ?></td>
        <td>Офис (квартира)</td>
        <td class="bordered" style="min-width: 35px;"><?= Html::encode($model->organization->apartment) ?></td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="4">1.3. Основной государственный регистрационный номер</td>
        <td colspan="4" class="no-padding">
            <table class="chars-table pull-right">
                <tr>
                    <?php
                    $ogrnChars = str_split(str_pad($model->organization->ogrn, 15));
                    foreach ($ogrnChars as $char) {
                        echo "<td>$char</td>";
                    }
                    ?>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4">1.4. Дата внесения записи в государственный реестр</td>
        <td colspan="4" class="no-padding">
            <table class="chars-table pull-right" style="margin: -1px 0;">
                <tr>
                    <?php
                    $dateEgrulChars = str_split(str_pad($model->organization->dateEgrul, 10));
                    foreach ($dateEgrulChars as $char) {
                        echo "<td>$char</td>";
                    }
                    ?>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">1.5. ИНН/КПП</td>
        <td colspan="6" class="no-padding">
            <table class="chars-table pull-right">
                <tr>
                    <?php
                    $innKppChars = str_split(
                        str_pad($model->organization->inn, 12)
                        . '/'
                        . str_pad($model->organization->kpp, 9)
                    );
                    foreach ($innKppChars as $char) {
                        echo "<td>$char</td>";
                    }
                    ?>
                </tr>
            </table>
        </td>
    </tr>
</table>

<h4>2. Реквизиты нерезидента (нерезидентов)</h4>
<table class="bordered full-width">
    <tr>
        <th rowspan="2">Наименование</th>
        <th colspan="2">Страна</th>
    </tr>
    <tr>
        <th>наименование</th>
        <th>код</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
    </tr>
    <?php foreach ($model->nonResidents as $item): ?>
        <tr>
            <td><?= Html::encode($item->name) ?></td>
            <td><?= Html::encode($item->countryName) ?></td>
            <td><?= Html::encode($item->countryCode) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h4>3. Сведения о кредитном договоре</h4>
<h5>3.1. Общие сведения о кредитном договоре</h5>
<table class="bordered full-width">
    <tr>
        <th rowspan="2">№</th>
        <th rowspan="2">Дата</th>
        <th colspan="2">Валюта кредитного договора</th>
        <th rowspan="2">Сумма кредитного договора</th>
        <th rowspan="2">Дата завершения исполнения обязательств по кредитному договору</th>
        <th colspan="2">Особые условия</th>
        <th rowspan="2">Код срока привлечения (предоставления)</th>
    </tr>
    <tr>
        <td>наименование</td>
        <td>код</td>
        <td>зачисление на счета за рубежом</td>
        <td>погашение за счет валютной выручки</td>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
    </tr>
    <tr>
        <th><?= Html::encode($model->loanAgreementNumber) ?></th>
        <th><?= Html::encode($model->loanAgreementDate) ?></th>
        <th><?= Html::encode($model->loanAgreementCurrencyDescription) ?></th>
        <th><?= Html::encode($model->loanAgreementCurrencyCode) ?></th>
        <th><?= formatCurrencyValue($model->loanAgreementAmount) ?></th>
        <th><?= Html::encode($model->loanAgreementEndDate) ?></th>
        <th><?= formatCurrencyValue($model->foreignAccountsTransferAmount) ?></th>
        <th><?= formatCurrencyValue($model->currencyIncomeRepaymentAmount) ?></th>
        <th><?= Html::encode($model->repaymentPeriodCode) ?></th>
    </tr>
</table>

<h5>3.2. Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору</h5>
<table class="bordered full-width">
    <tr>
        <th colspan="2">Валюта кредитного договора</th>
        <th rowspan="2">Сумма транша</th>
        <th rowspan="2">Код срока привлечения (предоставления) транша</th>
        <th rowspan="2">Ожидаемая дата поступления транша</th>
    </tr>
    <tr>
        <th>наименование</th>
        <th>код</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
    </tr>
    <?php foreach ($model->tranches as $item): ?>
        <tr>
            <td><?= Html::encode($model->loanAgreementCurrencyDescription) ?></td>
            <td><?= Html::encode($model->loanAgreementCurrencyCode) ?></td>
            <td><?= formatCurrencyValue($item->amount) ?></td>
            <td><?= Html::encode($item->paymentPeriodCode) ?></td>
            <td><?= Html::encode($item->receiptDate) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div>
    <?php
    $previousLoanAgreementUniqueNumber = preg_replace('/[^0-9a-z]+/i', '', $model->previousLoanAgreementUniqueNumber);
    renderUniqueLoanAgreementNumber($previousLoanAgreementUniqueNumber, 'pull-right');
    ?>
    <h5>4. Сведения о ранее присвоенном<br>кредитному договору уникальном<br>номере</h5>
</div>

<h4>5. Специальные сведения о кредитном договоре</h4>
<h5>5.1. Процентные платежи, предусмотренные кредитным договором (за исключением платежей по возврату основного долга)</h5>
<table class="bordered full-width">
    <tr>
        <th>Фиксированный размер процентной ставки, % годовых</th>
        <th>Код ставки ЛИБОР</th>
        <th>Другие методы определения процентной ставки</th>
        <th>Размер процентной надбавки (дополнительных платежей) к базовой процентной ставке, % годовых</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
    </tr>
    <tr>
        <td><?= Html::encode($model->fixedInterestRatePercent) ?></td>
        <td><?= Html::encode($model->fixedInterestRateLiborCodeName) ?></td>
        <td><?= Html::encode($model->otherPercentRateCalculationMethod) ?></td>
        <td><?= Html::encode($model->increaseRatePercent) ?></td>
    </tr>
</table>

<h5>5.2. Иные платежи, предусмотренные кредитным договором (за исключением платежей по возврату основного долга и процентных платежей, указанных в пункте 5.1)</h5>
<div class="bordered" style="min-height: 25px; padding: 5px;">
    <?= Html::encode($model->otherPayments) ?>
</div>

<div>
    <table class="bordered pull-right" style="width: 50%">
        <tr>
            <th style="width: 50%">Код валюты кредитного договора</th>
            <th>Сумма</th>
        </tr>
        <tr>
            <th>1</th>
            <th>3</th>
        </tr>
        <tr>
            <td><?= Html::encode($model->loanAgreementCurrencyCode) ?></td>
            <td><?= formatCurrencyValue($model->mainDebtAmount) ?></td>
        </tr>
    </table>
    <h5>5.3. Сумма задолженности по основному долгу на дату, предшествующую дате постановки на учет кредитного договора (присвоения уникального номера)</h5>
</div>

<h4>6. Справочная информация о кредитном договоре</h4>
<h5>6.1. Основания заполнения пункта 6.2</h5>
<table>
    <tr>
        <td>6.1.1. Сведения из кредитного договора</td>
        <td class="bordered text-center" style="min-width: 70px;"><?= $model->paymentScheduleReason == 1 ? 'X' : '' ?></td>
    </tr>
    <tr>
        <td>6.1.2. Оценочные данные</td>
        <td class="bordered text-center"><?= $model->paymentScheduleReason == 2 ? 'X' : '' ?></td>
    </tr>
</table>

<h5>6.2. Описание графика платежей по возврату основного долга и процентных платежей</h5>
<table class="bordered full-width">
    <tr>
        <th rowspan="3">№ п/п</th>
        <th rowspan="3">Код валюты кредитного договора</th>
        <th colspan="4">Суммы платежей по датам их осуществления, в единицах валюты кредитного договора</th>
        <th rowspan="3">Описание особых условий</th>
    </tr>
    <tr>
        <th colspan="2">по погашению основного долга</th>
        <th colspan="2">в счет процентных платежей</th>
    </tr>
    <tr>
        <th>дата</th>
        <th>сумма</th>
        <th>дата</th>
        <th>сумма</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
    </tr>
    <?php foreach ($model->paymentScheduleItems as $i => $item): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= Html::encode($model->loanAgreementCurrencyCode) ?></td>
            <td><?= Html::encode($item->debtDate) ?></td>
            <td><?= formatCurrencyValue($item->debtAmount) ?></td>
            <td><?= Html::encode($item->interestDate) ?></td>
            <td><?= formatCurrencyValue($item->interestAmount) ?></td>
            <td><?= Html::encode($item->specialConditions) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<table class="full-width" style="margin-top: 20px">
    <tr>
        <td style="width: 65%"><h5 style="display: inline; margin: 0;">6.3. Отметка о наличии отношений прямого инвестирования</h5></td>
        <td class="bordered text-center" style="width: 70px;"><?= $model->isDirectInvesting ? 'X' : '' ?></td>
        <td></td>
    </tr>
    <tr><td colspan="3"></td></tr>
    <tr>
        <td><h5 style="display: inline; margin: 0;">6.4. Сумма залогового или другого обеспечения</h5></td>
        <td class="bordered" colspan="2"><?= formatCurrencyValue($model->depositAmount) ?></td>
    </tr>
</table>

<h5>6.5. Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе</h5>
<table class="full-width bordered">
    <tr>
        <th>№ п/п</th>
        <th>Наименование нерезидента</th>
        <th>Код страны места нахождения нерезидента</th>
        <th>Предоставляемая сумма денежных средств, в единицах валюты кредитного договора</th>
        <th>Доля в общей сумме кредита (займа), %</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
    </tr>
    <?php foreach ($model->receipts as $i => $receipt): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= Html::encode($receipt->beneficiaryName) ?></td>
            <td><?= Html::encode($receipt->beneficiaryCountryCode) ?></td>
            <td><?= formatCurrencyValue($receipt->amount) ?></td>
            <td><?= Html::encode($receipt->shareOfLoanAmount) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div style="font-size: 14px; margin-top: 30px;">
    <p>
        <strong>Электронные подписи</strong><br>
        <?php foreach ($typeModel->signatureInfo->signatures as $signature): ?>
            <?= Html::encode($signature->uid) ?><br>
        <?php endforeach; ?>
    </p>
    <p><strong>Дата представления в Банк:</strong> <?= $receiveDate ? $receiveDate->format('d.m.Y') : '' ?></p>
    <p><strong>Дата принятия Банком:</strong> <?= $acceptDate ? $acceptDate->format('d.m.Y') : '' ?></p>
    <p><strong>Дата отказа Банком:</strong> <?= $rejectDate ? $rejectDate->format('d.m.Y') : '' ?></p>
    <p><strong>Причина отказа:</strong> <?= Html::encode($rejectReason) ?></p>
</div>

<style>
    .bordered, table.bordered td, table.bordered th {
        border: 1px solid black;
    }
    td, th {
        min-height: 14px;
        min-width: 7px;
        padding: 5px;
    }
    .no-padding {
        padding: 0 !important;
    }
    .chars-table td {
        border: 1px solid black;
        width: 17px;
        padding: 5px 0;
        text-align: center;
    }
    th {
        font-weight: normal;
        text-align: center;
        vertical-align: top;
    }
    .full-width {
        width: 100%;
    }
    h5 {
        font-weight: bold;
    }
    .unique-number-table td {
        padding: 9px 5px;
    }
</style>
