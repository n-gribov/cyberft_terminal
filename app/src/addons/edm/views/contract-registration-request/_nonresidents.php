<?php
/**
 * Вывод таблицы с информацией о нерезидентах по документу "Паспорт сделки"
 * Опционально могут быть выведены резиденты, которые предоставили кредит
*/

/**
 * @var Array $childObjectData
 * @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident $nonresident
 */

use addons\edm\helpers\EdmHelper;
use common\helpers\Html;

// Список кодов стран
$countryCodesList = EdmHelper::countryCodesList();
?>

<table class="table">
    <tr>
        <th><?=Yii::t('edm', 'Nonresident')?></th>
        <th><?=Yii::t('edm', 'Country code')?></th>
        <th><?=Yii::t('app/participant', 'Country')?></th>
        <?php if ($credit) : ?>
            <th><?=Yii::t('edm', 'Amount')?></th>
            <th><?=Yii::t('edm', 'Nonresident percent')?></th>
        <?php endif ?>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($childObjectData as $uuid => $nonresident): ?>

        <?php
            // Выводим резидентов согласно переданному режиму
            if ($credit != $nonresident->isCredit) {
                continue;
            }

        ?>

        <tr>
            <td><?=$nonresident->name?></td>
            <td><?=$nonresident->countryCode?></td>
            <td><?=$countryCodesList[$nonresident->countryCode]?></td>
            <?php if ($credit) : ?>
                <td><?=$nonresident->amount?></td>
                <td><?=$nonresident->percent?></td>
            <?php endif ?>
            <td><?=Html::a(Yii::t('yii', 'Update'), '#', [
                    'class' => 'update-nonresident',
                    'data' => [
                        'uuid' => $uuid,
                        'title' => Yii::t('edm', 'Nonresident'),
                        'credit' => $credit
                    ]
                ])?></td>
            <td><?=Html::a(Yii::t('app', 'Delete'), '#', [
                    'class' => 'delete-nonresident',
                    'data' => [
                        'uuid' => $uuid,
                        'credit' => $credit
                    ]
                ])?></td>
        </tr>
    <?php endforeach; ?>
</table>
