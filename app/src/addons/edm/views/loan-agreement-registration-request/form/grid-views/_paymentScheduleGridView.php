<?php

use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

/** @var \yii\web\View $this */
/** @var LoanAgreementRegistrationRequestForm\PaymentScheduleItem[] $models */

// Вывести страницу
echo $this->render(
    '_nestedItemsListGridView',
    [
        'id' => 'payment-schedule-grid-view',
        'models' => $models,
        'modelClass' => LoanAgreementRegistrationRequestForm\PaymentScheduleItem::class,
        'dataColumns' => [
            'debtDate',
            [
                'attribute' => 'debtAmount',
                'format' => ['decimal', 2]
            ],
            'interestDate',
            [
                'attribute' => 'interestAmount',
                'format' => ['decimal', 2]
            ],
            'specialConditions',
        ],
    ]
);
