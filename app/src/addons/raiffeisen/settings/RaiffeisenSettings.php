<?php
namespace addons\raiffeisen\settings;

use common\settings\BaseSettings;
use Yii;
use yii\helpers\ArrayHelper;

class RaiffeisenSettings extends BaseSettings
{
    public $gatewayUrl = '';
    public $exportXml = false;
    public $signaturesNumber = 1;
    public $useAutosigning = false;
    public $processAsyncRequestsInterval = 1;
    public $requestIncomingDocumentsInterval = 5;
    public $requestIncomingDocumentsTimeFrom = '03:00';
    public $requestIncomingDocumentsTimeTo = '23:59';

    public function attributeLabels()
    {
        return [
            'gatewayUrl'                       => Yii::t('app/raiffeisen', 'Universal Payment Gate URL'),
            'exportXml'                        => Yii::t('app', 'Activate XML export'),
            'processAsyncRequestsInterval'     => Yii::t('app/raiffeisen', 'Interval in minutes'),
            'requestIncomingDocumentsInterval' => Yii::t('app/raiffeisen', 'Interval'),
            'requestIncomingDocumentsTimeFrom' => Yii::t('app/raiffeisen', 'Start time'),
            'requestIncomingDocumentsTimeTo'   => Yii::t('app/raiffeisen', 'End time'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['exportXml'], 'boolean'],
                [['gatewayUrl', 'requestIncomingDocumentsTimeFrom', 'requestIncomingDocumentsTimeTo'], 'string'],
                [
                    [
                        'processAsyncRequestsInterval', 'requestIncomingDocumentsInterval',
                        'requestIncomingDocumentsTimeFrom', 'requestIncomingDocumentsTimeTo',
                    ],
                    'required'
                ],
                ['processAsyncRequestsInterval', 'integer', 'min' => 1],
                ['requestIncomingDocumentsInterval', 'integer', 'min' => 5],
            ]
        );
    }

    public function getIncomingDocumentsRequestIntervalOptions(): array
    {
        return array_reduce(
            [5, 10, 15, 20, 30, 60],
            function ($carry, $interval) {
                $carry[$interval] = Yii::t('yii', '{delta, plural, =1{1 minute} other{# minutes}}', ['delta' => $interval]);
                return $carry;
            },
            []
        );
    }
}
