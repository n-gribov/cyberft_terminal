<?php

use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

/** @var \yii\web\View $this */
/** @var LoanAgreementRegistrationRequestForm\NonResident[] $models */

// Вывести страницу
echo $this->render(
    '_nestedItemsListGridView',
    [
        'id' => 'non-residents-grid-view',
        'models' => $models,
        'modelClass' => LoanAgreementRegistrationRequestForm\NonResident::class,
        'dataColumns' => [
            'name',
            'countryCode',
            'countryName',
        ],
    ]
);
