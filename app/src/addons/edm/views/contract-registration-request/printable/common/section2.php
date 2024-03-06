<?php
/**
 * Секция 2 - Реквизиты нерезидента (нерезидентов)
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

use addons\edm\helpers\EdmHelper;

// Список кодов стран
$countryCodesList = EdmHelper::countryCodesList();

?>

<div class="section section-2">
    <div class="section-header">2. Реквизиты нерезидента (нерезидентов)</div>
    <table class="nonresidents-table">
        <tr>
            <td colspan="5" rowspan="2">Наименование</td>
            <td colspan="5">Страна</td>
        </tr>
        <tr>
            <td>наименование</td>
            <td>код</td>
        </tr>
        <tr>
            <td colspan="5">1</td>
            <td>2</td>
            <td>3</td>
        </tr>
        <?php foreach($model->nonresidentsItems as $nonresident): ?>
            <tr>
                <td colspan="5"><?=$nonresident->name?></td>
                <td><?=$nonresident->countryName?></td>
                <td><?=$nonresident->numericCountryCode?></td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
