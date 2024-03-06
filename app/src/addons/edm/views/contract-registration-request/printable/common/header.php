<?php

/**
 * Общая для всех типов документов шапка печатной формы
 * @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model
 * @var \addons\edm\models\DictOrganization $organization
 * @var \addons\edm\models\DictBank $bank
*/

// Номер паспорта сделки в виде массива для удобного вывода
$passportNumberArr = str_split($model->passportNumber);
?>
<div class="top-1">
    Код формы по ОКУД 0406005
</div>
<div class="top-2">
    Форма 1
</div>
<div class="bank-field-block"><?=$model->organizationBankName?></div>
<div class="bank-title-block">Наименование банка ПС</div>
<div class="document-header">
    <div class="document-header-title">Паспорт сделки</div>
    <div class="document-header-date">
        <div class="document-header-date-label">от</div>
        <div class="document-header-date-field"><?=$model->date?></div>
    </div>
    <div class="document-header-number">
        <div class="document-header-number-label">№</div>
        <div class="document-header-number-field">
            <table>
                <tr>
                    <?php foreach($passportNumberArr as $value) : ?>
                        <td><?= $value ?></td>
                    <?php endforeach ?>
                </tr>
            </table>
        </div>
    </div>
</div>
