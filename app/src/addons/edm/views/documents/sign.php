<?php

use yii\helpers\Html;

/** @var \common\document\Document $document */
/** @var string $pageTitle */
/** @var string $backUrl */
/** @var string|array $data */
/** @var string $algorithm */

$this->title = $pageTitle;

?>

<div class="panel-body">
    <?=
    Html::a(
        Yii::t('app', 'Back'),
        $backUrl,
        [
            'id' => 'backLink',
            'class' => 'btn btn-default'
        ]
    )
    ?>
</div>

<?php
// Вывести форму подписания
echo $this->render('@common/views/signing/_signForm',
        ['document' => $document, 'data' => $data, 'algorithm' => $algorithm]
);

