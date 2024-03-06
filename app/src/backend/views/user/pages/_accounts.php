<?php

use common\widgets\GridView;

/** @var \yii\web\View $this */
/** @var \common\models\User $model */
/** @var array $accounts */
// Создать таблицу для вывода
echo GridView::widget([
    'emptyText'    => Yii::t('other', 'No entries found'),
    'id' => 'accounts-access-grid-view',
    'summary'      => false,
    'dataProvider' => $accounts,
    'columns' => [
        [
            'attribute' => 'organization',
            'label' => Yii::t('edm', 'Organization'),
        ],
        [
            'attribute' => 'name',
            'label' => Yii::t('edm', 'Title'),
        ],
        [
            'attribute' => 'number',
            'label' => Yii::t('edm', 'Account number'),
        ],
        [
            'attribute' => 'currency',
            'label' => Yii::t('edm', 'Currency'),
        ],
        [
            'attribute' => 'bankName',
            'label' => Yii::t('edm', 'Bank'),
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => Yii::t('app/user', 'Access'),
            'contentOptions' => ['class' => 'account-access-cell'],
            'checkboxOptions' => function ($model) {
                return [
                    'value' => $model['id'],
                    'checked' => $model['allow'],
                    'data' => [
                        'permission' => 'access',
                    ],
                ];
            },
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => Yii::t('app/user', 'Signature permission'),
            'contentOptions' => ['class' => 'account-access-cell'],
            'checkboxOptions' => function ($model) {
                return [
                    'value' => $model['id'],
                    'checked' => $model['canSignDocuments'],
                    'disabled' => !$model['allow'],
                    'data' => [
                        'permission' => 'signature',
                    ],
                ];
            },
        ],
    ],
]);

$this->registerJs(<<<JS
function getAction(checkbox) {
    var permission = $(checkbox).attr('data-permission');
    switch (permission) {
        case 'access':
            return checkbox.checked ? 'grant' : 'revoke';
        case 'signature':
            return checkbox.checked ? 'grantSignature' : 'revokeSignature';
        default:
            return null;
    }
}

$('#accounts-access-grid-view').on('change', '.account-access-cell input:checkbox', function () {
    var accountId = this.value;
    var action = getAction(this);
    var cell = $(this).closest('.account-access-cell');
    var row = $(this).closest('tr');
    var accessCheckbox = row.find('input:checkbox[data-permission=access]');
    var signatureCheckbox = row.find('input:checkbox[data-permission=signature]');
    var self = this;
    $.ajax({
        url: 'set-account-access',
        type: 'POST',
        data: {
            userId: {$model->id},
            accountId: accountId,
            action: action
        },
        beforeSend: function () {
            cell.addClass('updating');
        },
        success: function (response) {
            cell.removeClass('updating');
            accessCheckbox.prop('checked', response.isGranted);
            signatureCheckbox.prop('checked', response.canSignDocuments);
            signatureCheckbox.prop('disabled', !response.isGranted);
            if (response.flashes) {
                BootstrapAlert.removeAll('.well.well-content');
                BootstrapAlert.showAll('.well.well-content', response.flashes);
            }
        }
    });
});
JS
);

$this->registerCss(<<<CSS
.account-access-cell {
    background: none;
    min-height: 55px;
}
.account-access-cell.updating {
    background: url('/img/spinner.gif') no-repeat 2px center;
}
.account-access-cell.updating input[type=checkbox] {
    opacity: 0;
}
CSS
);
