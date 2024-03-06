<?php

use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

/** @var \yii\web\View $this */
/** @var LoanAgreementRegistrationRequestForm\Tranche[] $models */
/** @var array $params */

echo $this->render(
    '_nestedItemsListGridView',
    [
        'id' => 'tranches-grid-view',
        'models' => $models,
        'modelClass' => LoanAgreementRegistrationRequestForm\Tranche::class,
        'dataColumns' => [
            [
                'label' => Yii::t('edm', 'Loan agreement currency'),
                'value' => function () use ($params) { return $params['currencyName'] ?? ''; },
                'contentOptions' => ['class' => 'currency-name'],
            ],
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2]
            ],
            'paymentPeriodName',
            'receiptDate',
        ],
    ]
);
