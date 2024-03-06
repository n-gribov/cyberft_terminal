<?php
use yii\helpers\Html;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

?>

<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
  <?= Yii::t('app', 'Search'); ?>
</a>



<p class="pull-right">
    <?php
        echo Html::a('',
            '#',
            [
                'class' => 'btn-columns-settings glyphicon glyphicon-cog',
                'title' => Yii::t('app', 'Columns settings')
            ]
        );
    ?>
</p>

<div class="<?= (empty($filterStatus)) ? 'collapse' : 'collapse.in'; ?>" id="collapseSearch">
    <?php
        $formParams = ['method' => 'get'];
        $form = ActiveForm::begin($formParams);
        if (!isset($aliases)) {
            $aliases = [];
        }
    ?>

    <div class="row log-search">
        <div class="col-md-6">
            <?= FieldRange::widget([
                'form' => $form,
                'model' => $model,
                'label' => Yii::t('other', 'Document registration date'),
                'attribute1' => 'dateCreateFrom',
                'attribute2' => 'dateCreateBefore',
                'type' => FieldRange::INPUT_WIDGET,
                'widgetClass' => DateControl::classname(),
                'widgetOptions1' => [
                    'saveFormat' => 'php:Y-m-d',
                ],
                'widgetOptions2' => [
                    'saveFormat' => 'php:Y-m-d'
                ],
                'separator' => Yii::t('doc', '&larr; to &rarr;'),
            ]); ?>
        </div>
        <div class="col-sm-2">
            <p style="margin-top:29px;"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?></p>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>