<?php

/* @var $this yii\web\View */
/* @var $model common\models\CryptoproKey */

$this->title = Yii::t('app/fileact', 'Create cryptopro key');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/fileact', 'Cryptopro keys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileact-cryptopro-keys-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
