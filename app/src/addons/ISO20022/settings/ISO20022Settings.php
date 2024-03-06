<?php
namespace addons\ISO20022\settings;

use common\settings\BaseSettings;
use Yii;
use yii\helpers\ArrayHelper;

class ISO20022Settings extends BaseSettings
{
    public $sftpEnable = false;
    public $sftpHost = '';
    public $sftpPort = '';
    public $sftpUser = '';
    public $sftpPassword = '';
    public $sftpPath = '';
    public $enableCryptoProSign = false;
    public $useSerial = false;
    public $signaturesNumber = 1;
    public $useAutosigning = false;
    public $importSearchSenderReceiver = true;
    public $exportXml = false;
    public $keepOriginalFilename = false;
    public $useUniqueAttachmentName = true;
    public $useCompatibleSigning = true;
    public $exportIBankFormat = false;
    public $exportReceipts = false;

    public $typeCodes = [
        'RJCT' => ['ru' => 'Запрос на отзыв платежа', 'en' => 'Payment revocation request'],
        'CCGR' => ['ru' => 'Заявка на открытие / Изменение / Закрытие Паспорта Сделки', 'en' => ''],
        'CCDC' => ['ru' => 'Справка о Подтверждающих Документах', 'en' => ''],
        'CCTC' => ['ru' => 'Справка о Валютных Операциях', 'en' => ''],
        'CCDC' => ['ru' => 'Подтверждающий Документ (Копия контракта / счета / и т.д.)', 'en' => ''],
        'FRLS' => ['ru' => 'Распоряжение по транзитному счету', 'en' => ''],
        'PMTM' => ['ru' => 'Запрос на изменение реквизитов платежа', 'en' => 'Payment requisites change request'],
        'PMTU' => ['ru' => 'Запрос на срочное исполнение платежа', 'en' => 'Urgent payment execution request'],
        'CCRD' => ['ru' => 'Запрос, связанный с обслуживанием Корпоративной карты', 'en' => 'Corporative card maintenance request'],
        'DEPT' => ['ru' => 'Запрос, связанный с обслуживанием Депозита', 'en' => 'Deposit maintenance request'],
        'LOAN' => ['ru' => 'Запрос, связанный с обслуживанием Кредита', 'en' => 'Credit maintenance request'],
        'CASD' => ['ru' => 'Запрос, связанный с обслуживанием по Инкассации', 'en' => ''],
        'TRAD' => ['ru' => 'Запрос, связанный с Торговым Финансированием', 'en' => ''],
        'FCTR' => ['ru' => 'Запрос, связанный с обслуживанием по Факторингу', 'en' => ''],
        'OVRD' => ['ru' => 'Запрос/Подтверждение по Овердрафту', 'en' => ''],
        'FREX' => ['ru' => 'Запрос/Подтверждение по Конверсионной сделке', 'en' => ''],
        'SECU' => ['ru' => 'Запрос в Депозитарий', 'en' => ''],
        'PAYR' => ['ru' => 'Запрос/информация, связанная с Зарплатным проектом', 'en' => ''],
        'LTBK' => ['ru' => 'Запрос, связанный с обслуживанием', 'en' => 'Maintenance request'],
        'OTHR' => ['ru' => 'Другой запрос в банк', 'en' => 'Other request'],
        'EBSR' => ['ru' => 'Запрос в Службу поддержки систем ДБО', 'en' => 'DBO System support request'],
    ];

    public function attributeLabels()
    {
        return [
            'sftpEnable' => Yii::t('app/iso20022', 'Enable SFTP'),
            'sftpHost' => Yii::t('app/iso20022', 'Host name'),
            'sftpPort' => Yii::t('app/iso20022', 'Host port'),
            'sftpUser' => Yii::t('app/iso20022', 'User name'),
            'sftpPassword' => Yii::t('app/iso20022', 'Password'),
            'sftpPath' => Yii::t('app/iso20022', 'Path'),
            'enableCryptoProSign' => Yii::t('app/settings', 'Enable CryptoPro sign'),
            'useSerial' => Yii::t('app/iso20022', 'Use certificate serial number instead of hash'),
            'exportXml' => Yii::t('app', 'Activate XML export'),
            'importSearchSenderReceiver' => Yii::t('app/iso20022', 'Search sender and receiver inside document on import'),
            'typeCodes' => Yii::t('app/iso20022', 'Document type codes'),
            'keepOriginalFilename' => Yii::t('app/iso20022', 'Keep original filename on document export'),
            'useUniqueAttachmentName' => Yii::t('app/iso20022', 'Generate unique attachment filename'),
            'useCompatibleSigning' => Yii::t('app/settings', 'Use old signature template'),
            'exportIBankFormat' => Yii::t('app/iso20022', 'Export documents to iBank format'),
            'exportReceipts' => Yii::t('app/iso20022', 'Export receipts'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['sftpEnable', 'enableCryptoProSign', 'importSearchSenderReceiver',
                'useSerial', 'exportXml', 'keepOriginalFilename', 'useUniqueAttachmentName',
                'useCompatibleSigning', 'exportIBankFormat', 'exportReceipts'], 'boolean'],
            [['sftpHost', 'sftpUser', 'sftpPassword', 'sftpPath'], 'string'],
            ['sftpPort', 'integer'],
        ]);
    }

    public function getTypeCodeList($lang = null)
    {
        if (is_null($lang)) {
            $lang = Yii::$app->language;
        }
        if ($lang != 'ru' && $lang != 'en') {
            $lang = 'ru';
        }
        $out = [];
        foreach($this->typeCodes as $key => $titles) {
            $out[$key] = $key . ': ' . $titles[$lang];
        }

        return $out;
    }

}