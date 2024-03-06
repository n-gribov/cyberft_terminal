<?php
/**
 * Секция 3 - Сведения о кредитном договоре
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */
/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche $tranche */
?>

<div class="section section-3">
    <div class="section-header">3. Сведения о кредитном договоре</div>
    <div class="section-subheader">3.1. Общие сведения о кредитном договоре</div>
    <div class="section3-1-field">
        <table class="crr-loan-table">
            <tr>
                <td rowspan="2">№</td>
                <td rowspan="2">Дата</td>
                <td colspan="2">Валюта кредитного договора</td>
                <td rowspan="2">Сумма кредитного договора</td>
                <td rowspan="2">Дата завершения исполнения обязательств по кредитному договору</td>
                <td colspan="2">Особые условия</td>
                <td rowspan="2">Код срока привлечения (предоставления)</td>
            </tr>
            <tr>
                <td>наименование</td>
                <td>код</td>
                <td>зачисление на счета за рубежом</td>
                <td>погашение за счет валютной выручки</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
            </tr>
            <tr>
                <td><?=$model->passportTypeNumberPrintable?></td>
                <td><?=$model->signingDate?></td>
                <td><?=$model->currencyDescription?></td>
                <td><?=$model->currencyCode?></td>
                <td><?=$model->amountPrintable?></td>
                <td><?=$model->completionDate?></td>
                <td><?=$model->creditedAccountsAbroad?></td>
                <td><?=$model->repaymentForeignCurrencyEarnings?></td>
                <td><?=$model->codeTermInvolvement?></td>
            </tr>
        </table>
    </div>
    <div class="section-subheader">3.2. Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору</div>
    <div class="section3-2-field">
        <table class="crr-loan-table">
            <tr>
                <td colspan="2">Валюта кредитного договора</td>
                <td rowspan="2">Сумма транша</td>
                <td rowspan="2">Код срока привлечения (предоставления) транша</td>
                <td rowspan="2">Ожидаемая дата поступления транша</td>
            </tr>
            <tr>
                <td>наименование</td>
                <td>код</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
            </tr>
            <?php foreach($model->tranchesItems as $tranche): ?>
                <tr>
                    <td><?=$model->currencyDescription?></td>
                    <td><?=$model->currencyCode?></td>
                    <td><?=$tranche->trancheAmountPrintable?></td>
                    <td><?=$tranche->termCodePrintable?></td>
                    <td><?=$tranche->datePrintable?></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
</div>
