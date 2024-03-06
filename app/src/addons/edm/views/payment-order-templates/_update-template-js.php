<?php

/** @var \yii\web\View $this */

$confirmOrganizationUpdate = Yii::t('edm', 'Payer organization requisites in this template are outdated. Do you want to update them from organization settings?');
$this->registerJs("
    $('body').on('click', '.template-load-link', function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        if ($(this).data('is-outdated') == 1) {
            if (confirm('$confirmOrganizationUpdate')) {
                url += '&updateOrganizationRequisites=1';
            }
        }
        location.href = url;
    });
");
