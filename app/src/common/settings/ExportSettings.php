<?php

namespace common\settings;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Реализация сущности Settings,
 * которая хранит индивидуальные настройки экспорта для терминалов
 * Class ExportSettings
 * @package common\settings
 */
class ExportSettings extends BaseSettings
{
    public $edmExportXml = false;
    public $ISO20022ExportXml = false;
    public $swiftfinExportXml = false;
    public $fileactExportXml = false;
    public $finzipExportXml = false;
    public $SBBOLExportXml = false;
    public $sbbol2ExportXml = false;
    public $participantExportXml = false;
    public $VTBExportXml = false;
    public $raiffeisenExportXml = false;
    public $useSwiftfinFormat = false;
    public $exportXmlPath = '/transport';
    public $exportStatusReports = false;

    public function attributeLabels()
    {
        return [
            'edmExportXml' => Yii::t('app', 'edm'),
            'ISO20022ExportXml' => Yii::t('app', 'ISO20022'),
            'swiftfinExportXml' => Yii::t('app', 'swiftfin'),
            'fileactExportXml' => Yii::t('app', 'fileact'),
            'finzipExportXml' => Yii::t('app', 'finzip'),
            'SBBOLExportXml' => Yii::t('app', 'SBBOL'),
            'sbbol2ExportXml' => Yii::t('app', 'SBBOL2'),
            'participantExportXml' => Yii::t('app/participant', 'Members'),
            'VTBExportXml' => Yii::t('app', 'VTB'),
            'raiffeisenExportXml' => Yii::t('app', 'Raiffeisen'),
            'useSwiftfinFormat' => Yii::t('app/terminal', 'Export documents in SWIFT FIN format'),
            'exportStatusReports' => Yii::t('app', 'Export status reports'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [
                [
                    'edmExportXml', 'ISO20022ExportXml',
                    'swiftfinExportXml', 'fileactExportXml',
                    'finzipExportXml', 'participantExportXml', 'VTBExportXml',
                    'useSwiftfinFormat', 'exportStatusReports',
                ], 'boolean'
            ],
            ['terminalId', 'required']
        ]);
    }

    /**
     * Проверка использования
     * индивидуальных настроек экспорта терминала
     */
    public function hasSettings()
    {
        return ($this->edmExportXml
           || $this->ISO20022ExportXml
           || $this->swiftfinExportXml
           || $this->fileactExportXml
           || $this->finzipExportXml
           || $this->participantExportXml
           || $this->VTBExportXml
           || $this->SBBOLExportXml
           || $this->sbbol2ExportXml
           || $this->raiffeisenExportXml
           || $this->useSwiftfinFormat
           || $this->exportStatusReports
       );
    }

    /**
     * Проверка необходимости экспорта для модуля
     * @param $service
     */
    public function serviceNeedsExport($service)
    {
        $property = $service . 'ExportXml';

        return isset($this->$property) ? $this->$property : false;
    }

}