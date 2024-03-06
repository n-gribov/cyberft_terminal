<?php
use yii\helpers\Html;
use common\models\User;

    // Авторизация документов недоступна администраторам

    if (Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
        echo ' ' . Html::a(Yii::t('app', 'Authorize'),
                ['/' . $model->typeGroup . '/documents/authorize', 'id' => $model->id],
                ['class' => 'btn btn-success']);
    }
?>