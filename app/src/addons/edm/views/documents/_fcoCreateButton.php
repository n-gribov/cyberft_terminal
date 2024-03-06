<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var string $wizardType */

echo Html::a(
    Yii::t('app', 'Create'),
    ['foreign-currency-operation-wizard/create'],
    [
        'id'    => 'btnCreate',
        'class' => 'btn btn-success',
    ]
);

$this->registerJS(<<<JS
    $('#btnCreate').on('click', function(e) {
        e.preventDefault();

        // Очистка имеющегося кэша
        $.get('/wizard-cache/clear-fco-cache');

        $('#fcoCreateModalTitle').html('Создание валютной операции');
        $('#fcoCreateModalButtons').hide();
        $('#fcoCreateModal .modal-body').html('');
        $('#fcoUpdateModal .modal-body').html('');

        $.ajax({
            url: '/edm/foreign-currency-operation-wizard/create?type={$wizardType}',
            type: 'get',
            success: function(answer) {
                $('#fcoCreateModal .modal-body').html(answer);
            }
        });

        $('#fcoCreateModal').modal('show');
    });
JS
);
