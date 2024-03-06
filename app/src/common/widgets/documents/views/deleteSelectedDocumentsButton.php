<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var string $checkboxesSelector */
/** @var string $deleteUrl */
/** @var string $entriesSelectionCacheKey */

ActiveForm::begin([
    'action'  => Url::toRoute([$deleteUrl], ['method' => 'post']),
    'method'  => 'post',
    'options' => [
        'id'    => 'delete-selected-documents-form',
        'style' => 'display: inline;'
    ]
]);

if ($entriesSelectionCacheKey) {
    echo Html::hiddenInput('entriesSelectionCacheKey', $entriesSelectionCacheKey);
}

echo Html::button(
    Yii::t('app', 'Delete selected'),
    [
        'id'       => 'delete-selected-documents-button',
        'class'    => 'btn btn-danger',
        'disabled' => true
    ]
);

ActiveForm::end();

$language = Yii::$app->language;
$this->registerJS(<<<JS
    function checkSelectedDocuments() {
        var form = $('#delete-selected-documents-form');
        form.find('input[name="id[]"]').remove();
        $('$checkboxesSelector').each(function(index, element) {
            var documentId = $(element).data('id');
            if (documentId != null && $(element).is(':checked:visible:enabled')) {
                form.append($('<input name="id[]" type="hidden" />').val(documentId));
            }
        });
        form.find('#delete-selected-documents-button').prop('disabled', form.find('input[name="id[]"]').size() == 0);
    }


    $('#delete-selected-documents-button').click(function() {
        var form = $('#delete-selected-documents-form');
        var documentsCount = form.find('input[name="id[]"]').size();
        var message = createDeleteDocumentsConfirmationMessage(documentsCount, '$language');
        if (confirm(message)) {
            form.trigger('submit');
        }
    });

    $('body').on('click', '$checkboxesSelector', function(e) {
        // костыль для ie
        var checkbox = $(e.target);
        if (checkbox.hasClass('select-on-check-all')) {
            $('$checkboxesSelector').filter(':visible:enabled').prop('checked', checkbox.is(':checked'));
        }

        checkSelectedDocuments();
    });
    
    checkSelectedDocuments();
JS
);
