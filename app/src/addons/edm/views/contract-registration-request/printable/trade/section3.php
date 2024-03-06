<?php
/**
 * Секция 3 - Общие сведения о контракте
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

?>

<div class="section section-3">
    <div class="section-header">3. Общие сведения о контракте</div>
    <table class="contract-data">
        <tr>
            <td rowspan="2">№</td>
            <td rowspan="2">Дата</td>
            <td colspan="2">Валюта контракта</td>
            <td rowspan="2">Сумма контракта</td>
            <td rowspan="2">Дата завершения исполнения обязательств по контракту</td>
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
            <td>6</td>
        </tr>
        <tr>
            <td><?=$model->passportTypeNumberPrintable?></td>
            <td><?=$model->signingDate?></td>
            <td><?=$model->currencyDescription?></td>
            <td><?=$model->currencyCode?></td>
            <td><?=$model->amountPrintable?></td>
            <td><?=$model->completionDate?></td>
        </tr>
    </table>
</div>
