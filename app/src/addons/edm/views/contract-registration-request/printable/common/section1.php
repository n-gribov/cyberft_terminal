<?php
/**
 * Секция 1 - Сведения о резиденте
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

// Преобразование в массивы для удобного вывода данных
// ОГРН
$ogrnArr = str_split($model->ogrn);

// Дата внесения в ЕГРЮЛ
$egrulArr = str_split($model->dateEgrul);

// ИНН/КПП
$innArr = str_split($model->inn);
$kppArr = str_split($model->kpp);
// Между значениями ИНН/КПП должен быть разделитель '/'
$innKppArr = array_merge($innArr, ['/'], $kppArr);

?>

<div class="section section-1">
    <div class="section-header">1. Сведения о резиденте</div>
    <div class="section-1-item-1">
        <div class="section-1-item-1-label">1.1. Наименование</div>
        <div class="section-1-item-1-field"><?=$model->organizationName?></div>
    </div>
    <div class="section-1-item-2 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-2-label-1">
            1.2. Адрес:
        </div>
        <div class="section-1-item-address-label-2 section-1-item-2-label-2">
            Субъект Российской Федерации
        </div>
        <div class="section-1-item-address-field section-1-item-2-field"><?=$model->state?></div>
    </div>
    <div class="section-1-item-3 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-3-label-1"></div>
        <div class="section-1-item-address-label-2 section-1-item-3-label-2">Район</div>
        <div class="section-1-item-address-field section-1-item-3-field"><?=$model->district?></div>
    </div>
    <div class="section-1-item-4 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-4-label-1"></div>
        <div class="section-1-item-address-label-2 section-1-item-4-label-2">Город</div>
        <div class="section-1-item-address-field section-1-item-4-field"><?=$model->city?></div>
    </div>
    <div class="section-1-item-5 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-5-label-1"></div>
        <div class="section-1-item-address-label-2 section-1-item-5-label-2">Населенный пункт</div>
        <div class="section-1-item-address-field section-1-item-5-field"><?=$model->locality?></div>
    </div>
    <div class="section-1-item-5 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-5-label-1"></div>
        <div class="section-1-item-address-label-2 section-1-item-5-label-2">Улица (проспект, переулок и т.д.)</div>
        <div class="section-1-item-address-field section-1-item-5-field"><?=$model->street?></div>
    </div>
    <div class="section-1-item-6 section-1-item-address">
        <div class="section-1-item-address-label-1 section-1-item-6-label-1"></div>
        <div class="section-1-item-address-label-2">
            <div class="section-1-item-address-label-2-data section-1-item-address-label-2-data-1">
                <div class="section-1-item-address-label-2-data-1-label">Номер дома (владение)</div>
                <div class="section-1-item-address-label-2-data-1-field"><?=$model->buildingNumber?></div>
            </div>
            <div class="section-1-item-address-label-2-data section-1-item-address-label-2-data-2">
                <div class="section-1-item-address-label-2-data-2-label">Корпус (строение)</div>
                <div class="section-1-item-address-label-2-data-2-field"><?=$model->building?></div>
            </div>
            <div class="section-1-item-address-label-2-data section-1-item-address-label-2-data-3">
                <div class="section-1-item-address-label-2-data-3-label">Офис (квартира)</div>
                <div class="section-1-item-address-label-2-data-3-field"><?=$model->apartment?></div>
            </div>
        </div>
    </div>
    <div class="section-1-item-7">
        <div class="section-1-item-7-title">1.3. Основной государственный регистрационный номер</div>
        <div class="section-1-item-7-field">
            <table>
                <tr>
                    <?php foreach($ogrnArr as $value): ?>
                        <td><?=$value?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
    <div class="section-1-item-8">
        <div class="section-1-item-8-title">1.4. Дата внесения записи в государственный реестр</div>
        <div class="section-1-item-8-field">
            <table>
                <tr>
                    <?php foreach($egrulArr as $value): ?>
                        <td><?=$value?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
    <div class="section-1-item-9">
        <div class="section-1-item-9-title">1.5. ИНН/КПП</div>
        <div class="section-1-item-9-field">
            <table>
                <tr>
                    <?php foreach($innKppArr as $value): ?>
                        <td><?=$value?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
</div>
