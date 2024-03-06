<?php
/**
 * Секция 5 - Сведения о ранее оформленном паспорте сделки по контракту
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

// Номер ранее использованого паспорта сделки
if ($model->existedPassport) {
    $existedPassportNumberArr = str_split($model->existedPassport);
} else {
    $existedPassportNumberArr = [
        '', '', '', '', '', '', '', '', '/',
        '', '', '', '', '/',
        '', '', '', '', '/',
        '', '/', ''
    ];
}

// Заголовок в зависимости от типа документа
$title = 'Сведения о ранее оформленном паспорте сделки по';

if ($model->passportType == $model::PASSPORT_TYPE_TRADE) {
    $title .= ' контракту';
} elseif ($model->passportType == $model::PASSPORT_TYPE_LOAN) {
    $title .= ' кредитному договору';
}

?>


<div class="section section-6">
    <div class="section-6-header"><div class="number">6.</div><?=$title?></div>
    <div class="section-6-field">
        <table>
            <tr>
                <?php foreach($existedPassportNumberArr as $value): ?>
                    <td><?=$value?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
</div>
