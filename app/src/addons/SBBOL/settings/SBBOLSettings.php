<?php
namespace addons\SBBOL\settings;

use common\settings\BaseSettings;
use Yii;
use yii\helpers\ArrayHelper;

class SBBOLSettings extends BaseSettings
{
    public $gatewayUrl = '';
    public $exportXml = false;
    public $signaturesNumber = 1;
    public $useAutosigning = false;
    public $requestYesterdaysStatements = false;
    public $requestYesterdaysStatementsTimeFrom = '03:00';
    public $requestYesterdaysStatementsTimeTo = '03:30';
    public $requestTodaysStatements = false;
    public $requestTodaysStatementsTimeFrom = '06:00';
    public $requestTodaysStatementsTimeTo = '23:00';
    public $requestTodaysStatementsInterval = 60;

    public function attributeLabels()
    {
        return [
            'gatewayUrl'                      => Yii::t('app/sbbol', 'Universal Payment Gate URL'),
            'requestYesterdaysStatements'     => Yii::t('app/sbbol', 'Automatically request statements for previous day (at 03:00 AM)'),
            'requestTodaysStatements'         => Yii::t('app/sbbol', 'Automatically request today\'s statements'),
            'exportXml'                       => Yii::t('app', 'Activate XML export'),
            'requestTodaysStatementsTimeFrom' => Yii::t('app/sbbol', 'Start time'),
            'requestTodaysStatementsTimeTo'   => Yii::t('app/sbbol', 'End time'),
            'requestTodaysStatementsInterval' => Yii::t('app/sbbol', 'Interval'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['exportXml'], 'boolean'],
                [['gatewayUrl', 'requestTodaysStatementsTimeFrom', 'requestTodaysStatementsTimeTo'], 'string'],
                [['requestTodaysStatementsInterval', 'requestTodaysStatementsTimeFrom', 'requestTodaysStatementsTimeTo'], 'required'],
                ['requestTodaysStatementsInterval', 'integer', 'min' => 15],
            ]
        );
    }

    public function getStatementRequestIntervalOptions(): array
    {
        return array_reduce(
            [15, 20, 30, 60],
            function ($carry, $interval) {
                $carry[$interval] = Yii::t('yii', '{delta, plural, =1{1 minute} other{# minutes}}', ['delta' => $interval]);
                return $carry;
            },
            []
        );
    }
}
