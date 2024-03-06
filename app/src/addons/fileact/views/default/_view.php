<?php

use yii\web\View;
//use common\modules\certManager\models\Cert;

/** @var View $this */

//$checkboxJS = <<<JS
//$('#showSignature').click(function() {
//    if ($(this).is(':checked')) {
//        $('#signList').show();
//    } else {
//        $('#signList').hide();
//    }
//});
//JS
//;
//
//$this->registerJs($checkboxJS, View::POS_READY);

echo $this->render('_download',  ['model' => $model]);
