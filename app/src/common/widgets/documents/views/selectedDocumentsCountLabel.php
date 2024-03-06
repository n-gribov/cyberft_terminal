<?php
/**
 * @var string $checkboxesSelector
 * @var integer $initialCount
 * @var \yii\web\View $this
 */
?>

<div id="selected-documents-count-label" class="label label-success hidden"></div>

<?php
$this->registerJS(<<<JS
    window.SelectedDocumentsCountLabel = {
        checkboxesSelector: '',
        
        initialize: function (checkboxesSelector, initialCount) {
            this.checkboxesSelector = checkboxesSelector;
            if (checkboxesSelector != '') {
                $(checkboxesSelector).on('click', this.onCheckboxesChange.bind(this));
                this.onCheckboxesChange();
            }
            if (initialCount > 0) {
                this.updateCount(initialCount);
            }
        },
        
        onCheckboxesChange: function (e) {
            if (e != null) {
                // костыль для ie
                var checkbox = $(e.target);
                if (checkbox.hasClass('select-on-check-all')) {
                    $(this.checkboxesSelector).filter(':visible:enabled').prop('checked', checkbox.is(':checked'));
                }
            }
            
            var count = $(this.checkboxesSelector).filter('[name="selection[]"]:checked').size();
            this.updateCount(count);
        },
        
        updateCount: function (count) {
            $('#selected-documents-count-label')
                .toggleClass('hidden', count == 0)
                .html('Выбрано ' + count);
        }
    };
    SelectedDocumentsCountLabel.initialize('$checkboxesSelector', Number('$initialCount'));
JS
);
