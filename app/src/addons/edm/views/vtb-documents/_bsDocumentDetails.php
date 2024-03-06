<?php

use addons\edm\models\VTBDocument\presenters\BSDocumentFieldsPresenter;
use addons\edm\models\VTBDocument\presenters\BSDocumentTablePresenter;
use common\document\Document;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/** @var View $this */
/** @var Document $document */
/** @var BSDocument $bsDocument */

$fieldsPresenter = new BSDocumentFieldsPresenter($bsDocument::TYPE, $bsDocument->getFields());
?>

<div class="row">
    <div class="col-xs-12">
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model'      => $bsDocument,
            'template'   => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => $fieldsPresenter->buildDetailViewAttributes($bsDocument, $document->id),
        ]) ?>
    </div>
</div>
<?php
$tableFields = array_filter(
    $fieldsPresenter->getFields(),
    function ($field) {
        return $field instanceof BlobTableField;
    },
    ARRAY_FILTER_USE_BOTH
);

foreach ($tableFields as $fieldId => $field) {
    /** @var BlobTableField $field */
    if ($fieldsPresenter->isExcluded($fieldId) || empty($bsDocument->$fieldId)) {
        continue;
    }
    echo '<h4>' . Html::encode($fieldsPresenter->getLabel($fieldId)) . '</h4>';

    /** @var BSDocument[] $tableDocuments */
    $tableDocuments = $bsDocument->$fieldId;
    $tablePresenter = new BSDocumentTablePresenter($field->recordType, $tableDocuments[0]->getFields());

    echo GridView::widget([
        'options' => ['class' => 'table-records-grid'],
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $tableDocuments,
            'pagination' => false
        ]),
        'rowOptions' => function ($model, $key, $index, $grid) use ($fieldId, $document) {
            return [
                'data' => [
                    'document-id' => $document->id,
                    'field-id' => $fieldId,
                    'index' => $key,
                ],
            ];
        },
        'columns' => array_merge(
            $tablePresenter->buildGridViewColumns(),
            [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="ic-eye"></span>',
                                '#',
                                ['class' => 'show-table-record-button']
                            );
                        }
                    ],
                    'contentOptions' => ['class' => 'text-right', 'width' => '55px']
                ],
            ]
        )
    ]);
}
?>
<?php
$attachmentFields = array_filter(
    $fieldsPresenter->getFields(),
    function ($field) {
        return $field instanceof AttachmentField;
    },
    ARRAY_FILTER_USE_BOTH
);
?>
<?php if (count($attachmentFields)) : ?>
<h4><?= Yii::t('edm', 'Attached documents') ?></h4>
<?php
    foreach($attachmentFields as $fieldName => $value) {
        $attachments = $bsDocument->$fieldName;
        // Создать таблицу для вывода
        echo GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $attachments,
                'pagination' => false
            ]),
            'columns' => [
                [
                    'attribute' => 'fileName',
                    'label' => Yii::t('edm', 'File name'),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{download}',
                    'buttons' => [
                        'download' => function($url, $model, $key) use ($document, $fieldName) {
                            return Html::a(
                                Html::tag('span', '', ['class' => 'glyphicon glyphicon-download']),
                                ['/edm/vtb-documents/download-attachment', 'id' => $document->id,
                                    'fieldId' => $fieldName, 'index' => $key],
                                [
                                    'class' => 'delete-button',
                                    'title' => Yii::t('app', 'Download'),

                                ]);
                        }
                    ],
                    'contentOptions' => ['class' => 'text-right text-nowrap', 'width' => '35px']
                ],
            ]
        ]);
    }
?>
<?php endif ?>
