<?php

use common\document\Document;
use common\modules\certManager\models\Cert;
use common\widgets\GridView;

/** @var Document $model */
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $model->getSignatureDataProvider(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER),
    'columns' => [
        [
            'attribute' => 'name',
            'label' => Yii::t('app/cert', 'Owner Name'),
        ],
        [
            'attribute' => 'fingerprint',
            'label' => Yii::t('app/cert', 'Certificate Thumbprint'),
        ],
        [
            'attribute' => 'signingTime',
            'label' => Yii::t('app/message', 'Signing Time'),
        ]
    ],
]);
