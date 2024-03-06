<?php

use common\models\cyberxml\CyberXmlDocument;

/* @var $model Document */

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';

    return;
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';

    return;
}

?>
<pre><?php
        if ($typeModel) {
            echo $typeModel->getSource()->getContentReadable();
        } else {
            echo 'Неподдерживаемый тип данных';
        }
    ?>
</pre>
