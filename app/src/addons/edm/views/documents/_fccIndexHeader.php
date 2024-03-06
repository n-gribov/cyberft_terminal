<?php

use common\helpers\Html;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SelectedDocumentsCountLabel;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * Шаблон добавляет управляющие кнопки для журнала документов валютного контроля
 */
$selectedDocumentsCount = 0;

if ($userCanDeleteDocuments) {
    $language = Yii::$app->language;
    $saveEntriesUrl = Url::toRoute(["select-entries?tabMode={$tabMode}"]);

    $selectedDocumentsCount = !empty($cachedEntries['entries']) ? count($cachedEntries['entries']) : 0;

    $checkboxJS = <<<JS
    var documentsCount = Number($selectedDocumentsCount);
    function sendSaveEntriesRequest(entries) {
        $.post(
            '$saveEntriesUrl',
            {
                entries: entries
            },
            function(data) {
                var selectedIds = JSON.parse(data);
                $('#btnDelete').toggleClass('disabled', selectedIds.length == 0);
                documentsCount = selectedIds.length;
                SelectedDocumentsCountLabel.updateCount(documentsCount);
                console.log('data', data);
            }
        );
    };

    $('.select-on-check-all').click(function(e) {
        // костыль для ie
        $('[name="selection[]"]:visible:enabled').prop('checked', $(this).is(':checked'));

        var entries = $('[name="selection[]"]').map(
            function(index, element) {
                return {
                    id: element.value,
                    checked: $(element).is(':checked')
                };
            }
        ).get();
        sendSaveEntriesRequest(entries);
    });

    $('body').on('click', "[name='selection[]']", function(e) {
        var entries = [
            {
                id: this.value,
                checked: $(this).is(':checked')
            },
        ];
        sendSaveEntriesRequest(entries);
    });

    $('#btnDelete').click(function() {
        var confirmMessage = createDeleteDocumentsConfirmationMessage(documentsCount, '$language');

        return confirm(confirmMessage);
    });
JS;

    $this->registerJs($checkboxJS, View::POS_READY);
}
?>
<div class="clearfix">
    <div class="pull-left">
        <?php
        if ($userCanCreateDocuments) {
            if (count($createButtonsOptions) > 1) {
                echo ButtonDropdown::widget([
                    'label' => Yii::t('document', 'Create document'),
                    'dropdown' => [
                        'items' => $createButtonsOptions,
                    ],
                    'options' => ['class' => 'btn btn-success'],
                ]);
            } else {
                $createButtonOptions = $createButtonsOptions[0];
                echo Html::a($createButtonOptions['label'], [$createButtonOptions['url']], ['class' => 'btn btn-success']);
            }
            echo '&nbsp;';
        }

        if ($userCanDeleteDocuments) {
            $disabledDelete = empty($cachedEntries['entries']) ? ' disabled' : '';
            if ($selectedDocumentsCount > 0 || $hasDeletableDocuments) {

                echo DeleteSelectedDocumentsButton::widget([
                    'checkboxesSelector' => '.checkbox-selection, .select-on-check-all',
                    'deleteUrl' => '/edm/documents/delete',
                ]);
                echo SelectedDocumentsCountLabel::widget(['initialCount' => $selectedDocumentsCount]);
            }
        }
        ?>
    </div>
    
    <div class="pull-right">
        <?php
        echo Html::a('',
            '#',
            [
                'class' => 'btn-columns-settings glyphicon glyphicon-cog',
                'title' => Yii::t('app', 'Columns settings')
            ]
        );
        ?>
    </div>
    <div class="pull-right" style="margin-right:1em">
        <?php
        $form = ActiveForm::begin([
            'id' => $searchModel->formName(),
            'method' => 'get',
        ]);
        echo $form->field($searchModel, 'showDeleted')->checkbox([
           'onChange' => '$("#' . $searchModel->formName() . '").submit();'
        ]);

        ActiveForm::end();
        ?>
    </div>
</div>
