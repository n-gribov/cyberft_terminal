<?php

use common\helpers\Html;
use yii\web\View;

$optionsTerminalId = [
    'id' => 'beneficiary-select',
    'class' => 'form-control'
];

?>

<div class="row">
    <div class="col-lg-9">
        <?= Html::label(Yii::t('app/cert', 'Beneficiaries for which the key is used'))?>
    </div>
</div>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-9" >
        <?= Html::dropDownList('beneficiaryNotSelected', null, [], $optionsTerminalId)?>
    </div>
    <div class="col-lg-3">
        <a id="add-user-beneficiary" href="#" class="btn btn-primary" data-id="<?=$keyId?>"><?= Yii::t('app', 'Add') ?></a>
    </div>
</div>

<div class="grid-view" id="beneficiary-selected">
    <table class="table table-dblclick table-striped table-hover">
        <thead>
            <tr>
                <th>Терминал</th>
                <th>Наименование</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php

$script = <<<JS
    var beneficiarySelectedInput = $('input[name="beneficiarySelectedInput"]');
    var beneficiaryAll = $beneficiaryAll;
    var beneficiarySelected = $beneficiarySelected;
    var beneficiaryListAvailable = false;

    function refillBeneficiary() {
        beneficiaryListAvailable = false;
        $('#beneficiary-select').empty();
        $('#beneficiary-select').attr('disabled', 'disabled');
        $('#add-user-beneficiary').attr('disabled', 'disabled');

        $('#beneficiary-selected table tbody').empty();
        $('#beneficiary-selected table tbody').append('<tr><td>Не определено</td></tr>');

        let noElementsSelectFlag = true;
        let noElementsSelectedFlag = true;
        let selected = [];
        $('#beneficiary-selected table tbody').empty();
        for (let i in beneficiarySelected) {
            let benef = beneficiarySelected[i];
            let tableRow = '<tr>';
            tableRow += '<td class="terminal-id">' + benef.terminalId+'</td>';
            tableRow += '<td class="title">' + benef.title + '</td>';
            tableRow += '<td><a class="delete-beneficiary" href="#" data-id=' + benef.terminalId + '><span class="glyphicon glyphicon-trash"></span></a></td>';
            tableRow += '</tr>';
            $('#beneficiary-selected table tbody').append(tableRow);
            selected[benef.terminalId] = true;
        }

        $.each(beneficiaryAll, function(keyAll, valueAll) {
            if (!selected[valueAll.terminalId]) {
                $('#beneficiary-select')
                .append($('<option>', { value : valueAll.terminalId })
                .text( valueAll.terminalId + ' (' + valueAll.title + ')' ));
                beneficiaryListAvailable = true;
            }
        });

        if (beneficiaryListAvailable) {
            $('#beneficiary-select').removeAttr('disabled');
            $('#add-user-beneficiary').removeAttr('disabled');
        }
    }

    $('.replace-certificate-button').click(function() {
        $('#uploaduserauthcertform-certid').val($(this).data('id'));
        certificateFileInput.trigger('click');
    });

    $('body').on('click', '#add-user-beneficiary', function(e) {
        e.preventDefault();
        if (!beneficiaryListAvailable) {
            return false;
        }
        let optionText = $('#beneficiary-select option:selected').text();
        let terminalId = optionText.slice(0, 12);
        let title = optionText.slice(14, -1);

        beneficiarySelected.push({
            terminalId: terminalId,
            title: title
        });

        beneficiarySelectedInput.val(JSON.stringify(beneficiarySelected));
        refillBeneficiary();
    });

    $('body').on('click', '.delete-beneficiary', function(e) {
        e.preventDefault();

        let deletedIndex = 0;
        for (let i = 0; i < beneficiarySelected.length; i++) {
            if ($(this).data('id') == beneficiarySelected[i].terminalId) {
                deletedIndex = i;
                break;
            }
        }

        beneficiarySelected.splice(deletedIndex, 1);

        beneficiarySelectedInput.val(JSON.stringify(beneficiarySelected));
        refillBeneficiary();

    });

    $( document ).ready(function() {
        let benef = JSON.stringify(beneficiarySelected);
        beneficiarySelectedInput.val(benef);
        refillBeneficiary();
    });
JS;

$this->registerJs($script, View::POS_READY);
?>
