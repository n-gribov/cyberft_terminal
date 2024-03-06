<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use addons\swiftfin\SwiftfinModule;

/* @var $model SwtContainer */
/* @var $this yii\web\View */
/* @var $mtDispatcher yii\web\View */

$mtDispatcher = SwiftfinModule::getInstance()->mtDispatcher;
$request      = Yii::$app->request;
/**
 * @var integer
 * Флажок, обозначающий вход из 2-го шага визарда, отображенного в режиме "Текст".
 * Наличие этого флага здесь обозначает ошибку валидации текстовых данных и факт
 * повторной отрисовки данной страницы с целью исправления текстовых данных.
 * 0|1|null
 */
$myRawMode = null;
// Флажок отвечающий за режим отображения визарда "Текст"/"Форма"
if ($request->isPost) {
    $myRawMode = $request->post('rawMode');
    $viewMode  = $request->post('viewmode', $viewMode);
} else {
    if ($request->isGet) {
        $myRawMode = $request->get('rawMode');
        $viewMode  = $request->get('viewmode', $viewMode);
    }
}
// Устанавливаем режим просмотра формы, если он не указан в запросе
if ($myRawMode !== null) {
    $viewMode = (empty($viewMode) ? ($myRawMode == 1 ? 'text' : 'form') : $viewMode);
} else if (!isset($viewMode) || !$viewMode) {
    // Если параметра нет, то принимаем его значение по умолчанию
    $viewMode = (empty($viewMode) ? 'form' : $viewMode);
}

if ($viewMode !== '') {
    $formMode = ($viewMode === 'form' ? 0 : 1);
} else {
    $formMode = Yii::$app->request->post('rawMode');
}

// Проверка наличия причины корректировки

if (Yii::$app->cache->exists('swiftfin/wizard/edit-' . Yii::$app->session->id)) {
    $documentId = Yii::$app->cache->get('swiftfin/wizard/edit-' . Yii::$app->session->id);
    $document = \common\document\Document::findOne($documentId);

    if (isset($document->extModel) && !empty($document->extModel->correctionReason)) {
        echo '<div class="correction-alert-width"><div class="alert alert-info">' . $document->extModel->correctionReason . '</div></div>';
    }
}

$tabItems = [
    [
        'label'   => Yii::t('doc/mt', 'Form mode'),
        'active'  => (0 === $formMode),
        'options' => [
            'data-toggle' => 'tab'
        ]
    ],
    [
        'label'   => Yii::t('doc/mt', 'Raw text mode'),
        'active'  => (1 === $formMode),
        'options' => [
            'data-toggle' => 'tab'
        ]
    ]
];
// отрубаем табы если не доступны режимы
if (!$formable) {
    unset($tabItems[0]);
} else if (!$textEdit) {
    unset($tabItems[1]);
}
?>
<?php $this->beginBlock('tab1'); ?>
<?php $form = ActiveForm::begin([
    'id'                     => 'docEdit',
    'type'                   => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan'               => 12,
    'formConfig'             => ['labelSpan' => 3],
    'enableClientValidation' => false,
    'enableAjaxValidation'   => false,
]);
?>

</style>

<?=Html::hiddenInput('viewmode', '')?>
<?=Html::hiddenInput('rawMode', 0)?>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt20 mt-new-ui">
                <div class="swt-help-btn">
                    <span>?</span>
                    <div class="swt-help-data">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (count($tabItems)>1) : ?>
                                    <div class="alert alert-info">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?=Yii::t('other', 'Switch editing modes by pressing F10')?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="col-sm-12">
                                Легенда:
                                <div class="row" style="margin-top:10px">
                                    <div class="col-sm-2 swt-tag-name">
                                        <div class="mt-required">XX</div>
                                    </div>
                                    <div class="col-sm-10" style="padding-top:10px;">
                                        Обязательный блок для заполнения
                                    </div>
                                </div>
                                <div class="row" style="margin-top:10px">
                                    <div class="col-sm-4">
                                        <input type="text" class="mt-required-input form-control">
                                    </div>
                                    <div class="col-sm-8" style="padding-top:10px;">
                                        Обязательный элемент в блоке
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		<?php
                    if (isset($model->view)) {
                        echo $this->render($model->view, ['model' => $model, 'form' => $form]);
                    } else {
                        echo $this->render($mtDispatcher->getViewPath($model->type), ['model' => $model, 'form' => $form]);
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row col-md-8">
            <div class="col-md-offset-4 col-md-8">
                <?=Html::a(Yii::t('app', 'Back'), ['/swiftfin/wizard/index'], ['name'  => 'send', 'class' => 'btn btn-default'])?>
                <?=Html::submitButton(Yii::t('app', 'Next'), ['name' => 'send', 'class' => 'btn btn-primary'])?>
            </div>
        </div>
    </div>
<?php
    if ($model->hasErrors()) {
	yii\bootstrap\Modal::begin([
            'id'     => 'errorsSummary',
            'header' => Yii::t('other', 'Form filling out error'),
	]);
	echo $form->errorSummary($model);
	$this->registerJs("$('#errorsSummary').modal('show');");
	yii\bootstrap\Modal::end();
    }
?>
<?php ActiveForm::end() ?>
<?php $this->endBlock('tab1') ?>
<?php $this->beginBlock('tab2') ?>
<?php $form = ActiveForm::begin([
    'id' => 'docEdit',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'enableClientValidation' => false,
    'formConfig' => [
        'labelSpan'  => 4,
        'showErrors' => false
    ]
])
?>
<?=Html::hiddenInput('viewmode', '')?>
<?=Html::hiddenInput('rawMode', 1)?>
    <div class="form-group mt20">
        <div class="col-sm-8">
            <?php
                $textareaOptions = ['rows' => 15];
                if (Yii::$app->cache->exists('swiftfin/template-text')) {
                    $textareaOptions['value'] = Yii::$app->cache->get('swiftfin/template-text');
                    // Очистка кэша шаблона
                    Yii::$app->cache->delete('swiftfin/template-text');
                }
            ?>
            <?=$form->field($model, 'body')->textarea($textareaOptions)?>
            <div class="col-md-offset-4 col-md-8"><?=$form->errorSummary($model)?></div>
            <div class="col-md-offset-4 col-md-8">
                <?=Html::a(
                    Yii::t('app', 'Back'),
                    ['/swiftfin/wizard/index'], [
                        'name'  => 'send',
                        'class' => 'btn btn-default'
                    ]
                )?>
                <?=Html::submitButton(Yii::t('app', 'Next'), ['name' => 'send', 'class' => 'btn btn-primary'])?>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php $this->endBlock('tab2'); ?>

<?= \yii\bootstrap\Nav::widget([
    'id' => 'formTabs',
    'options' => [
        'class' => 'nav nav-pills',
    ],
    'items' => $tabItems
])?>

<?php if (0 === $formMode): ?>
    <?= $this->blocks['tab1'] ?>
<?php else: ?>
    <?= $this->blocks['tab2'] ?>
<?php endif ?>

<?php
    if (count($tabItems) > 1) {
	$this->registerJs(<<<JS
        $(window).keydown(function (e) {
            if (121 == e.keyCode) {
                e.preventDefault();

                if ($('input[name=rawMode]').val()=='1') {
                    $('input[name=viewmode]').val('form');
                } else {
                    $('input[name=viewmode]').val('text');
                }
                $('#docEdit').submit();
                return false;
            }
        });

        $('li[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if ($('input[name=rawMode]').val()=='1') {
                $('input[name=viewmode]').val('form');
            } else {
                $('input[name=viewmode]').val('text');
            }
            $('#docEdit').submit();
            return false;
        });
        JS,
        yii\web\View::POS_END
    );
}
