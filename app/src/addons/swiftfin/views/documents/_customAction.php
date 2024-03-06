<?php
use yii\helpers\Html;
use common\models\User;

// Получить роль пользователя из активной сессии
if (Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
    // Авторизация документов недоступна администраторам
    echo ' ' . Html::a(Yii::t('app', 'Authorize'),
        ['/' . $model->typeGroup . '/documents/authorize', 'id' => $model->id],
        ['class' => 'btn btn-success']);
}
