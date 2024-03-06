<?php

use common\modules\certManager\models\Cert;
use yii\web\View;

/* @var $this View */
/* @var $model Cert */

$this->title = Yii::t('other', 'Edit: {id}', ['id' => $model->certId]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/cert', 'Certificates'), 'url' => ['index', 'role' => $model->role]];
$this->params['breadcrumbs'][] = ['label' => $model->certId, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/cert', 'Editing');

// Вывести форму редактирования сертификата
echo $this->render('_form', ['model' => $model]);

if ($model->isActive()) {
    $this->registerJs(<<<JS
        function deactivateCertModalConfirm() {
            $('#manage-cert').submit();
        }

        function getModelRole() {
            return $('#cert-role').val();
        }
    JS, View::POS_READY);

    // Вывести модальное окно деактивации сертификата
    echo $this->render('_deactivateCertModal', ['model' => $model, 'buttonId' => 'btn-manage-cert']);
}
