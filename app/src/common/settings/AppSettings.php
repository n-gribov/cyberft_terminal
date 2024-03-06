<?php

namespace common\settings;

use common\models\Processing;
use common\modules\api\models\AccessToken;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Реализация базовой сущности Settings, которая хранит настройки в mysql.
 * @property-read null|string $apiAccessToken
 * @property string $apiSecret
 */
class AppSettings extends BaseSettings
{
    /**
     * @var array $processing Processing settings
     */
    public $processing;

    /**
     * @var array $cftcp Cftcp client settings
     */
    public $cftcp;

    /**
     * @var array $stomp Stomp settings
     */
    public $stomp = [];

    public $exportXmlPath = '/transport';
    public $useCompatibleSigning = true;
    public $useZipBeforeEncrypt = false;
	public $useUtf8ZipFilenameEncoding = '';
    public $jobsEnabled = true;
    public $exportStatusReports = false;
    public $validateXmlOnImport = false;

    // Настройки для хранения общих, для всех модулей,
    // значений по настройкам подписания
    public $usePersonalAddonsSigningSettings = true;
    public $useAutosigning = false;
    public $qtySignings = 0;
    public $sbbolCustomerSenderName = '';
    public $sbbol2CustomerSenderName = '';

    // Настройки интеграции в API
    public $enableApi = false;
    public $encryptedApiSecret = '';
    public $useGlobalExportSettings = true;

    public $cyberftDirectoryVersion;

    public function init()
    {
        parent::init();

        if ($this->processing === null) {
            $this->saveDefaultSettings();
        }
    }

    public function attributeLabels()
    {
        return [
            'processing' => 'processing',
            'cftcp' => 'cftcp',
            'stomp' => 'stomp',
            'exportXmlPath' => 'exportXmlPath',
            'useCompatibleSigning' => Yii::t('app/settings', 'Use old signature template'),
            'useZipBeforeEncrypt' => Yii::t('app/settings', 'Use ZIP compression for outgoing documents'),
            'usePersonalAddonsSigningSettings' => Yii::t('app/settings', 'Use separate signing settings for each module'),
            'exportStatusReports' => Yii::t('app', 'Export status reports'),
            'enableApi' => Yii::t('app', 'Enable importing and exporting documents via API'),
            'apiAccessToken' => Yii::t('app', 'Access token'),
            'validateXmlOnImport' => Yii::t('app/settings', 'Validate documents against XSD on document import'),
			'useUtf8ZipFilenameEncoding' => Yii::t('app/settings', 'Use UTF-8 filename encoding in zip archive')
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [
                    [
                        'processing', 'cftcp', 'stomp',
                        'exportXmlPath', 'qtySignings',
                        'useGlobalExportSettings', 'validateXmlOnImport',
                    ],
                        'safe'
                    ],
                [
                    [
                        'useZipBeforeEncrypt',
                        'useCompatibleSigning',
                        'jobsEnabled',
                        'usePersonalAddonsSigningSettings',
                        'useAutosigning',
                        'exportStatusReports',
                        'enableApi',
                        'useGlobalExportSettings',
                        'validateXmlOnImport',
						'useUtf8ZipFilenameEncoding',
                    ], 'boolean'
                ]
            ]);
    }

    public function saveDefaultSettings()
    {
        $appSettings = require(Yii::getAlias('@common/config/settings.php'));

        $defaultProcessing = Processing::findOne(['isDefault' => true]);
        if ($defaultProcessing) {
            $appSettings['processing']['dsn'] = $defaultProcessing->dsn;
            $appSettings['processing']['address'] = $defaultProcessing->address;
        }

        $this->setAttributes($appSettings);
        $this->save();
    }

    public function getApiSecret()
    {
        if (!$this->encryptedApiSecret) {
            return '';
        }

        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        return \Yii::$app->security->decryptByKey(base64_decode($this->encryptedApiSecret), $encryptionKey);
    }

    public function setApiSecret($secret)
    {
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->encryptedApiSecret = base64_encode(\Yii::$app->security->encryptByKey($secret, $encryptionKey));
    }

    public function getApiAccessToken()
    {
        $secret = $this->getApiSecret();
        if (empty($secret)) {
            return null;
        }
        $accessToken = AccessToken::fromSecret($secret, $this->terminalId ?: null);
        return (string)$accessToken;
    }
}
