<?php

use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

/** @var \yii\web\View $this */
/** @var LoanAgreementRegistrationRequestForm\Receipt[] $models */

echo $this->render(
    '_nestedItemsListGridView',
    [
        'id' => 'receipts-grid-view',
        'models' => $models,
        'modelClass' => LoanAgreementRegistrationRequestForm\Receipt::class,
        'dataColumns' => [
            'beneficiaryName',
            'beneficiaryCountryName',
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'shareOfLoanAmount',
                'value' => function ($model, $params) {
                    return number_format($model->shareOfLoanAmount, 2, '.', ' ') . ' %';
                }
            ],
        ],
    ]
);
