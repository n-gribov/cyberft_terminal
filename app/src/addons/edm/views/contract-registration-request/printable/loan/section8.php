<?php

/**
 * Секция 8 - Специальные сведения о кредитном договоре
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

use addons\edm\helpers\EdmHelper;

?>

<div class="section section-8">
    <div class="section-header">8. Специальные сведения о кредитном договоре</div>
    <div class="section-subheader section-header-format"><div class="number">8.1.</div> Процентные платежи, предусмотренные кредитным договором<br>(за исключением платежей по возврату основного долга)</div>
    <div class="section-8-1-field">
        <table class="crr-loan-table">
            <tr>
                <td>Фиксированный размер процентной ставки,<br> % годовых</td>
                <td>Код ставки<br> ЛИБОР</td>
                <td>Другие методы<br> определения процентной ставки</td>
                <td>Размер процентной надбавки (дополнительных платежей)<br> к базовой процентной ставке, % годовых</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
            <tr>
                <td><?=$model->fixedRate?></td>
                <td><?=$model->codeLiborPrintable?></td>
                <td><?=$model->otherMethodsDeterminingRate?></td>
                <td><?=$model->bonusBaseRate?></td>
            </tr>
        </table>
    </div>
    <div class="section-subheader section-header-format"><div class="number">8.2.</div> Иные платежи, предусмотренные кредитным договором (за исключением платежей по возврату основного долга и процентных платежей, указанных в пункте 8.1)</div>
    <div class="section-8-2-field"><?=$model->otherPaymentsLoanAgreement?></div>
    <div class="section-8-3-field">
        <div class="section-8-3-field-label section-subheader section-header-format">
            <div class="number">8.3.</div> Сумма задолженности по основному долгу на дату, предшествующую дате оформления паспорта сделки
        </div>
        <div class="section-8-3-field-content">
            <table class="crr-loan-table section-8-3-table">
                <tr>
                    <td>Код валюты<br> кредитного договора</td>
                    <td>Сумма</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>
                        <?=$model->contractCurrencyName?>
                    </td>
                    <td>
                        <?=$model->amountMainDebt?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
