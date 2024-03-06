<?php

use yii\helpers\Url;
use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $model addons\VTB\models\VTBCryptoproCert */

$this->title = Yii::t('app/iso20022', 'Create certificate');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'VTB'), 'url' => Url::toRoute(['/VTB/documents'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/iso20022', 'Cryptopro certificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileact-cryptopro-cert-create">

    <p>
        <?=Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']);?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
