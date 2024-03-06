<?php

use common\modules\wiki\WikiModule;
use common\widgets\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

$this->title = WikiModule::t('default', 'Update page');
$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;


$this->beginBlock('pageActions');
echo common\helpers\Html::a(Yii::t('app', 'View'), $model->getUrl(), ['class' => 'btn btn-primary']);
$this->endBlock('pageActions');

// Вывести форму
echo $this->render('_form', ['model' => $model]);

echo '<h3>' . WikiModule::t('default', 'Attachments'). '</h3>';
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider'  => new ArrayDataProvider([
        'allModels' => $model->attachments
    ]),
    'actions'   => [
        'urlCreator' => function($action, $model, $key, $index) {
            switch ($action) {
                case 'view':
                    $link = Url::toRoute([
                        'default/attachment-download',
                        'id' => $model->id,
                        'inline' => $model->type == 'image' ? true :false
                    ]);
                    break;
                case 'update':
                    $link = Url::toRoute(['crud/attachment-update', 'id' => $model->id]);
                    break;
                case 'delete':
                    $link = Url::toRoute(['crud/attachment-delete', 'id' => $model->id]);
                    break;
                default:
                    $link = '';
                    break;
            }
            return $link;
        },
    ],
]);
