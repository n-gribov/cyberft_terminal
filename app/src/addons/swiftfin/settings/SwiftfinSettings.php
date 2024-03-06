<?php
namespace addons\swiftfin\settings;

use common\settings\BaseSettings;
use yii\helpers\ArrayHelper;
use Yii;

class SwiftfinSettings extends BaseSettings
{
    public $autoPrintMt = [];
    public $fixSender = false;
    public $validateOnImport = false;
    public $userVerificationRequired = false;
    public $userVerificationRules = [];
    public $signaturesNumber = 1;
    public $useAutosigning = false;

    /**
     * активация маршрутизации документов от ABS в swift
     * @var bool
     */
    public $swiftRouting = false;

    /**
     * директория документов от ABS
     * @var string
     */
    public $swiftRoutePath;

    /**
     * @var bool
     */
    public $exportIsActive = false;

    /**
     * Вкл. экспорт сообщений о доставке (CYB-830)
     * @var boolean
     */
    public $deliveryExport = false;

    /**
     * TTL в минутах для жизни недоставленного сообщения (CYB-830)
     * Для отживших cообщений в статусе PROCESSING будет генериться MT010
     * @var int
     */
    public $deliveryExportTTL;

    /**
     * @var string
     */
    public $exportExtension = 'swt';

    /**
     * Флаг разрешения/запрета экспорта XML-файлов
     * @var boolean
     */
    public $exportXml = false;
    public $accountsVerification = false;

    public $exportChecksum = false;
    public $approvalFormats = [];

    public function attributeLabels()
    {
        return [
            'fixSender' => Yii::t('app/terminal', 'Automatic correction of sender\'s address'),
            'swiftRouting' => Yii::t('app/terminal', 'Activate swift documents routing'),
            'validateOnImport' => Yii::t('app/swiftfin', 'Validate imported documents'),
            'accountsVerification' => Yii::t('app/terminal', 'Verify document accounts'),
            'swiftRoutePath' => Yii::t('app/terminal', 'Swift documents are redirected to'),
            'exportIsActive' => Yii::t('app/terminal', 'Export documents in SWIFT FIN format'),
            'exportExtension' => Yii::t('app/terminal', 'File extension for exported files'),
            'exportXml' => Yii::t('app', 'Activate XML export'),
            'deliveryExport' => Yii::t('app/terminal', 'Export MT011 document on delivery'),
            'deliveryExportTTL'=> Yii::t('app/terminal', 'TTL in minutes for undelivered documents'),
            'userVerificationRequired' => Yii::t('app/terminal', 'userVerificationRequired'),
            'userVerificationRules'=> Yii::t('app/terminal', 'userVerificationRules'),
            'exportChecksum' => Yii::t('app/swiftfin', 'Add checksum to MT documents on export'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['fixSender', 'swiftRouting', 'exportIsActive', 'exportXml', 'deliveryExport',
                'accountsVerification', 'exportChecksum', 'validateOnImport'], 'boolean'],
            ['deliveryExportTTL', 'number'],
            ['swiftRoutePath', 'string', 'max' => 255],
            ['exportExtension', 'string', 'max' => 5],
            ['swiftRoutePath', '\common\validators\PathValidator'],
            ['swiftRouting', 'validateDepend'],
            [['autoPrintMt','userVerificationRules'],'safe']
        ]);
    }

    public function validateDepend($attribute, $params)
    {
        $params['dependent'] = 'swiftRoutePath';
        if ($this->$attribute && !$this->{$params['dependent']}) {
            $this->addError($params['dependent'],Yii::t('app/terminal', 'Please, select a path to route SWIFT documents'));
        }
    }

    public function getApprovalEnabled($format)
    {
        return isset($this->approvalFormats[$format]['enabled']) ? $this->approvalFormats[$format]['enabled'] : false;
    }

    public function getApprovers($format)
    {
        return isset($this->approvalFormats[$format]['approvers']) ? $this->approvalFormats[$format]['approvers'] : [];
    }

    public function setApprovalEnabled($format, $enabled)
    {
        $this->approvalFormats[$format]['enabled'] = $enabled;
    }

    public function setApprovers($format, $approvers)
    {
        $this->approvalFormats[$format]['approvers'] = $approvers;
    }

}