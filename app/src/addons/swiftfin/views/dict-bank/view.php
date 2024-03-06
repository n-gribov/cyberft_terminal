<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model addons\swiftfin\models\SwiftFinDictBank */

$this->title					 = $model->name;
$this->params['breadcrumbs'][]	 = ['label' => Yii::t('doc/swiftfin', 'Banks Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][]	 = $this->title;
?>
<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'swiftCode',
        'branchCode',
        'name',
        'address',
    ],
])
?>
<div class="panel-footer">
    <?=
    Html::a(Yii::t('app', 'Back'), Yii::$app->request->getReferrer(),
        ['class' => 'btn btn-primary']);
    ?>
</div>
