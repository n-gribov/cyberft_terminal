<?php

use addons\ISO20022\models\Auth018Type;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;

use yii\helpers\Html;
use yii\web\View;

/** @var View $this */

$cyxDoc = $model->getCyberXml();
if (!$cyxDoc) {
    echo Yii::t('doc', 'View for this document is not defined');
} else {
    $typeModel = $cyxDoc->getContent()->getTypeModel();
    $attachableTypes = [Auth024Type::TYPE, Auth025Type::TYPE, Auth026Type::TYPE, Auth018Type::TYPE];
    $hasAttachment = in_array($model->type, $attachableTypes)
        && (
            // Если модель использует сжатие в zip
            $typeModel->useZipContent
            || ($typeModel instanceof Auth026Type && !empty($typeModel->embeddedAttachments))
        );

?>
<?= $typeModel->originalFilename ?>
<?php if ($hasAttachment): ?>
    <div class="panel panel-body">
        Вложение: <?= $typeModel->zipFilename ?>
        <?= Html::a(
            Yii::t('app', 'Download file'),
            ['download-attachment', 'id' => $model->id,],
            ['class' => 'btn btn-primary', 'style' => 'margin-left:2em']
        ) ?>
    </div>
<?php endif ?>

<?php
    $content = (string) $typeModel;
    if (!empty($content)) {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($content);
        $dom->encoding = 'UTF-8';
        $dom->formatOutput = true;
        $content = $dom->saveXml();
    }
    echo '<pre>' . htmlspecialchars($content) . '</pre>';
}
?>
