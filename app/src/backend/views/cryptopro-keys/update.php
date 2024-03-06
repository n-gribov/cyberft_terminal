<?php

use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CryptoproKey */

$this->title = Yii::t('app/fileact', 'Key #{id}', ['id' => $keyId]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/fileact', 'Cryptopro keys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileact-cryptopro-keys-update">
<?php
    if (Yii::$app->cache->exists('crypto-pro-back-link' . Yii::$app->session->id)) {
        $backUrl = Yii::$app->cache->get('crypto-pro-back-link' . Yii::$app->session->id);
    } else {
        $backUrl = Yii::$app->request->referrer;
    }

    echo Html::a(Yii::t('app', 'Back'), $backUrl, ['class' => 'btn btn-default']);

    // Вывести детали сетрификата
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
        'keyId' => $keyId,
        'terminalList' => $terminalList,
        'beneficiaryList' => $beneficiaryList,
        'dataProvider' => $dataProvider,
        'dataProviderBeneficiary' => $dataProviderBeneficiary,
    ]);
?>
</div>
