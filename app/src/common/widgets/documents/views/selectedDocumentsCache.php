<?php

/** @var \yii\web\View $this */
/** @var string $saveUrl */

$this->registerJs(<<<JS
    function sendSaveEntriesRequest(entries) {
        $.post(
            '$saveUrl',
            {
                entries: entries
            },
            function(data) {
                var selectedIds = JSON.parse(data);
                $('#btnDelete').toggleClass('disabled', selectedIds.length === 0);
                if (typeof SelectedDocumentsCountLabel !== 'undefined') {
                    SelectedDocumentsCountLabel.updateCount(selectedIds.length);
                }
            }
        );
    }

    $('.select-on-check-all').click(function(e) {
        // костыль для ie
        $('[name="selection[]"]:visible:enabled').prop('checked', $(this).is(':checked'));

        var entries = $("[name='selection[]']").map(
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
JS
);
