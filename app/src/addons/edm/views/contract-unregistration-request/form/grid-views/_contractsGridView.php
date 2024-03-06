<?php

use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;

/** @var \yii\web\View $this */
/** @var ContractUnregistrationRequestForm\Contract[] $models */
/** @var array $params */

echo $this->render(
    '_nestedItemsListGridView',
    [
        'id' => 'contracts-grid-view',
        'models' => $models,
        'modelClass' => ContractUnregistrationRequestForm\Contract::class,
        'dataColumns' => [
            'uniqueContractNumber',
            [
                'attribute' => 'uniqueContractNumberDate',
                'label' => \Yii::t('edm', 'Date'),
            ],
            'unregistrationGroundName',
            'unregistrationGroundCode',
        ],
    ]
);
