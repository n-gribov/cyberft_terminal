<?php

use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\widgets\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\web\View;
use addons\ISO20022\models\Pain002Type;
use addons\ISO20022\models\Auth027Type;

/* @var $this View */
/* @var $model Document */
/* @var $referencingDataProvider ActiveDataProvider */

if ($referencingDataProvider->totalCount < 1) {
	echo Yii::t('doc', 'No referencing documents found');
} else {
	echo GridView::widget([
		'emptyText' => Yii::t('app/diagnostic', 'No referencing documents'),
		'summary' => Yii::t('app/diagnostic', 'Shown from {begin} to {end} out of {totalCount} found'),
		'dataProvider' => $referencingDataProvider,
//'doubleClickView' => true,
        'rowOptions' => function ($model){
            $options['ondblclick'] = "window.location='" . Url::toRoute(['view', 'id' => $model->id]) . "'";

            return $options;
        },
		'columns' => [
			['attribute' => 'id', 'enableSorting' => false],
			['attribute' => 'direction', 'enableSorting' => false],
			['attribute' => 'dateCreate'],
			[
			    'attribute' => $model->direction === Document::DIRECTION_IN ? 'receiver' : 'sender',
                'enableSorting' => false,
            ],
			['attribute' => 'type', 'enableSorting' => false],
			['attribute' => 'uuid', 'enableSorting' => false],
			['attribute' => 'uuidReference', 'enableSorting' => false],
			[
				'attribute' => 'Status report',
				'format' => 'html',
				'value' => function($data, $params) use($model) {

                    if (in_array($data->type, ['CFTStatusReport', 'StatusReport', Pain002Type::TYPE, Auth027Type::TYPE])) {
                        $typeModel = CyberXmlDocument::getTypeModel($data->actualStoredFileId);
                        $result = '';
                        if (!empty($typeModel)) {
                            if ($data->type == Pain002Type::TYPE) {
                                $result = '<b>Status code:</b> ' . $typeModel->getStatusCodeByType($model->type) . '<br/>';
                                $result .= '<b>Error code:</b> ' . $typeModel->errorCode . '<br/>';
                                $result .= '<b>Error description:</b> ' . $typeModel->getErrorDescriptionByType($model->type) . '<br/>';
                            } else if ($data->type == Auth027Type::TYPE) {
                                $result = '<b>Status code:</b> ' . $typeModel->statusCode . '<br/>';
                                $result .= '<b>Error description:</b> ' . $typeModel->errorDescription;
                            } else {
                                $result = '<b>Status code:</b> ' . $typeModel->statusCode . '<br/>';
                                $result .= '<b>Error code:</b> ' . $typeModel->errorCode . '<br/>';
                                $result .= '<b>Error description:</b> ' . $typeModel->errorDescription;
                            }
                        }

                        return $result;
					} else if ($data->type == PaymentStatusReportType::TYPE) {
                        $typeModel = CyberXmlDocument::getTypeModel($data->actualStoredFileId);

                        if (!empty($typeModel)) {
                            $result = '<b>Status code:</b> ' . $typeModel->statusCode . '<br/>';
                            $result .= '<b>Description:</b> ' . $typeModel->statusDescription . '<br/>';
                            $result .= '<b>Reason:</b> ' . $typeModel->statusComment;
                        }

                        return $result;
                    }

					return '';
				}
			],
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function($action, $currModel, $key, $index ) use($model) {
                    return Url::toRoute([
                        'view',
                        'id' => $currModel->id,
                        'redirectUrl' => 'view?id=' . $model->id . '&mode=references'
                    ]);
                }
            ]
		],
	]);
}
