<?php

use common\models\form\UserServicesSettingsForm;
use common\widgets\GridView;
use yii\data\ArrayDataProvider;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;

/** @var UserServicesSettingsForm $servicesSettingsForm */
/** @var ArrayDataProvider $additionalServiceAccess */

?>

<div class="row">
    <div class="col-sm-6">
        <?php
            ActiveForm::begin([
                'action' => Url::to(['set-services-permissions', 'id' => $model->id])
            ]);
        ?>

        <?php
            $createCheckboxOptions = function ($attribute) {
                return function (UserServicesSettingsForm\Item $item, $key, $i, $col) use ($attribute): array {
                    return $item->createGridViewCheckboxOptions($attribute);
                };
            };

            echo GridView::widget([
                'emptyText'    => Yii::t('other', 'Services not found'),
                'summary'      => Yii::t('other', 'Available services'),
                'options' => [
                    'id' => 'documents-permissions-grid',
                ],
                'dataProvider' => $servicesSettingsForm->createItemsDataProvider(),
                'tableOptions' => ['class' => 'table table-hover table-striped'],
                'rowOptions' => function (UserServicesSettingsForm\Item $item) {
                    return $item->createGridViewRowOptions();
                },
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function (UserServicesSettingsForm\Item $item) {
                            if (!$item->isCollapsible()) {
                                return $item->name;
                            }
                            $collapseLink = Html::a(
                                Yii::t('app/user', 'Document types permissions'),
                                null,
                                [
                                    'class' => 'small',
                                    'data' => [
                                        'toggle' => 'collapse',
                                        'target' => ".child-row[data-service-id={$item->id}]",
                                    ],
                                ]
                            );
                            return "{$item->name}<br>$collapseLink";
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => CheckboxColumn::class,
                        'header' => Yii::t('app/user', 'Access'),
                        'name' => 'documentView',
                        'checkboxOptions' => $createCheckboxOptions('documentView'),
                    ],
                    [
                        'class' => CheckboxColumn::class,
                        'header' => Yii::t('app/user', 'Create documents'),
                        'name' => 'documentCreate',
                        'checkboxOptions' => $createCheckboxOptions('documentCreate'),
                    ],
                    [
                        'class' => CheckboxColumn::class,
                        'header' => Yii::t('app/user', 'Sign documents'),
                        'name' => 'documentSign',
                        'checkboxOptions' => $createCheckboxOptions('documentSign'),
                    ],
                    [
                        'class' => CheckboxColumn::class,
                        'header' => Yii::t('app/user', 'Delete documents'),
                        'name' => 'documentDelete',
                        'checkboxOptions' => $createCheckboxOptions('documentDelete'),
                    ],
                    [
                        'attribute' => 'additionalSettingsUrl',
                        'format' => 'html',
                        'value' => function (UserServicesSettingsForm\Item $item) {
                            return $item->additionalSettingsUrl
                                ? Html::a(
                                    '<span class="glyphicon glyphicon-cog"></span> ' . Yii::t('app/user', 'More...'),
                                    $item->additionalSettingsUrl
                                )
                                : '';
                        }
                    ]
                ]
            ])
        ?>

        <?php ActiveForm::end() ?>

        <?php
            ActiveForm::begin([
                'id' => 'form-user-update',
                'action' => Url::to(['set-additional-services-access', 'id' => $model->id])
            ]);
        ?>
        <?= GridView::widget([
            'emptyText'    => Yii::t('other', 'Services not found'),
            'summary'      => Yii::t('other', 'Available services'),
            'dataProvider' => $additionalServiceAccess,
            'options' => [
                'id' => 'additional-services-grid'
            ],
            'caption' => Yii::t('app/user', 'Additional services'),
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => Yii::t('other', 'Service'),
                ],
                [
                    'class' => CheckboxColumn::class,
                    'header' => Yii::t('doc', 'Enabled'),
                    'name' => 'serviceAccess',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return [
                            'value' => $key,
                            'checked' => $model['checked']
                        ];
                    }
                ],
                [
                    'label' => Yii::t('app/user', 'Additional settings'),
                    'format' => 'html',
                    'value' => function ($item, $params) {
                        return is_null($item['settingsUrl'])
                            ? ''
                            : Html::a(
                                '<span class="glyphicon glyphicon-cog"></span> ' . Yii::t('app/user', 'More...'),
                                $item['settingsUrl']
                            );
                    }
                ],
            ],
        ]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<?php

$script = <<< JS
$('#documents-permissions-grid input:checkbox').on('change', function() {
    var value = $(this).val();
    var name = $(this).attr('name');
    var isChecked = $(this).is(':checked');
    
    var valueParts = value.split(':');
    var serviceId = null;
    var documentTypeGroup = null;
    if (valueParts.length === 2) {
        serviceId = valueParts[0];
        documentTypeGroup = valueParts[1];
    } else {
        serviceId = valueParts[0];
    }

    var documentTypeGroupsCheckboxes = $('#documents-permissions-grid input:checkbox[name="' + name + '"][value^="' + serviceId + ':"][data-is-grantable=true]');
    if (documentTypeGroup) {
        var serviceCheckbox = $('#documents-permissions-grid input:checkbox[name="' + name + '"][value="' + serviceId + '"]');
        var allGroupsAreChecked = documentTypeGroupsCheckboxes.length === documentTypeGroupsCheckboxes.filter(':checked').length;
        serviceCheckbox.prop('checked', allGroupsAreChecked);
    } else {
        documentTypeGroupsCheckboxes.prop('checked', isChecked);
    }
    $(this).closest('form').trigger('submit');
});

$('#additional-services-grid input:checkbox').on('change', function() {
    $(this).closest('form').trigger('submit');
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCss('
    caption {
        color: #000;
        font-weight: bold;
    }
    #documents-permissions-grid table tr td:not(:first-child) {
        min-width: 100px
    }
    #documents-permissions-grid table .child-row td {
        border-top: none;
        font-size: 12px;
        padding-bottom: 3px;
        padding-top: 3px;
    }
    #documents-permissions-grid table .child-row td:first-child {
        padding-left: 15px;
    }
    #documents-permissions-grid table td {
        white-space: nowrap;
    }
    #documents-permissions-grid table .child-row td {
        white-space: normal;
    }
');
