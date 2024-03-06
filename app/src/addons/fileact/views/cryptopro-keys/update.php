<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\CryptoproKey */

$this->title = Yii::t('app/fileact', 'Key #{id}', ['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'FileAct'), 'url' => Url::toRoute(['/fileact'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/fileact', 'Cryptopro keys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileact-cryptopro-keys-update">
<?php
    // Вывести блок с деталями сетификата
    echo $this->render('_certDetail', [
        'cert' => openssl_x509_parse($model->certData),
    ]);

    if (Yii::$app->user->id == $model->userId) {
        $view = '_formActivate';
    } else {
        $view = '_form';
    }
    // Вывести форму
    echo $this->render($view, [
        'model' => $model,
    ]);
?>

</div>
