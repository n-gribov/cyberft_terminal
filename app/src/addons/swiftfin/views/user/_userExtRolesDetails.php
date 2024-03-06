<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var \addons\swiftfin\models\SwiftFinUserExt $userExtModel */
?>

<div class="panel panel-gray">
    <div class="panel-body">
        <?= DetailView::widget([
            'model' => $userExtModel,
            'attributes' => [
                [
                    'attribute' => 'role',
                    'format' => 'html',
                    'value' => Html::a($userExtModel->roleLabel,
                        Url::to(['/swiftfin/user-ext', 'id' => $userExtModel->user->id]))
                ]
            ],
        ]);
        ?>
    </div>
</div>
