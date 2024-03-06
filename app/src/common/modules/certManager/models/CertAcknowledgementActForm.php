<?php

namespace common\modules\certManager\models;

use common\base\Model;
use common\components\TerminalId;
use common\helpers\DateHelper;
use common\models\User;
use common\models\UserAuthCert;
use common\models\UserTerminal;
use common\modules\autobot\models\Autobot;
use common\modules\autobot\models\ControllerCertAcknowledgementActData;
use common\modules\certManager\components\ssl\Exception;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\utils\DocxTemplate;
use common\modules\participant\models\BICDirParticipant;
use Yii;

class CertAcknowledgementActForm extends Model
{
    const AGREEMENT_TYPE_ITO = 'ITO';
    const AGREEMENT_TYPE_DBO = 'DBO';

    public $certId;
    public $autobotId;
    public $userAuthCertId;
    public $agreementType;
    public $agreementNumber;
    public $agreementDate;
    public $participantFullName;
    public $participantName;
    public $signerFullName;
    public $signerPosition;
    public $signerAuthority;
    public $certOwnerFullName;
    public $certOwnerPosition;
    public $certOwnerRole;
    public $certOwnerPassportCountry;
    public $certOwnerPassportSeries;
    public $certOwnerPassportNumber;
    public $certOwnerPassportAuthority;
    public $certOwnerPassportIssueDate;
    public $certOwnerPassportAuthorityCode;
    public $certIssuer;
    public $certNotBefore;
    public $certNotAfter;
    public $certSubject;
    public $certFingerprint;

    private $actFileContent;
    private $actFileName;

    public function rules()
    {

        return [
            [
                [
                    'agreementType',
                    'participantFullName',
                    'signerFullName',
                    'signerPosition',
                    'signerAuthority',
                    'certOwnerFullName',
                    'certOwnerPosition',
                    'certOwnerPassportCountry',
                    'certOwnerPassportSeries',
                    'certOwnerPassportNumber',
                    'certOwnerPassportAuthority',
                    'certOwnerPassportIssueDate',
                    'certOwnerPassportAuthorityCode',
                ],
                'required'
            ],
            [
                [
                    'agreementType',
                    'agreementNumber',
                    'agreementDate',
                    'participantFullName',
                    'signerFullName',
                    'signerPosition',
                    'signerAuthority',
                    'certOwnerFullName',
                    'certOwnerPosition',
                    'certOwnerPassportCountry',
                    'certOwnerPassportSeries',
                    'certOwnerPassportNumber',
                    'certOwnerPassportAuthority',
                    'certOwnerPassportIssueDate',
                    'certOwnerPassportAuthorityCode',
                ],
                'safe'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'certId'                         => 'Id сертификата',
            'agreementType'                  => 'Тип договора',
            'agreementNumber'                => 'Номер договора',
            'agreementDate'                  => 'Дата договора',
            'participantFullName'            => 'Полное наименование организации',
            'signerFullName'                 => 'ФИО уполномоченного представителя клиента',
            'signerPosition'                 => 'Должность уполномоченного представителя клиента',
            'signerAuthority'                => 'Основания',
            'certOwnerFullName'              => 'ФИО владельца ключа',
            'certOwnerPosition'              => 'Должность владельца ключа',
            'certOwnerRole'                  => 'Роль владельца ключа',
            'certOwnerPassportCountry'       => 'Страна выдачи паспорта владельца ключа',
            'certOwnerPassportSeries'        => 'Серия паспорта владельца ключа',
            'certOwnerPassportNumber'        => 'Номер паспорта владельца ключа',
            'certOwnerPassportAuthority'     => 'Кем выдан паспорт владельца ключа',
            'certOwnerPassportIssueDate'     => 'Дата выдачи паспорта владельца ключа',
            'certOwnerPassportAuthorityCode' => 'Код подразделения, выдавшего паспорт владельца ключа',
            'certIssuer'                     => 'Кем выдан сертификат',
            'certNotBefore'                  => 'Дата начала действия сертификата',
            'certNotAfter'                   => 'Дата окончания действия сертификата',
            'certSubject'                    => 'Владелец сертификата',
            'certFingerprint'                => 'Отпечаток сертификата',
        ];
    }

    public function agreementTypeLabels()
    {
        return [
            self::AGREEMENT_TYPE_DBO => 'Дистанционное банковское обслуживание',
            self::AGREEMENT_TYPE_ITO => 'Информационно-техническое обслуживание',
        ];
    }

    public function agreementTypeLabel()
    {
        return $this->agreementTypeLabels()[$this->agreementType];
    }

    public static function buildForCert($certId)
    {
        $cert = Cert::findOne($certId);
        $certData = $cert->getCertificate();
        return new static([
            'certId'                   => $certId,
            'participantName'          => static::getParticipantNameByBic($cert->participantId),
            'certOwnerFullName'        => $cert->fullName,
            'certOwnerPosition'        => $cert->post,
            'certOwnerRole'            => $cert->role,
            'certOwnerPassportCountry' => 'РФ',
            'certIssuer'               => Cert::formatCertAttributes(@$certData->issuerName),
            'certNotBefore'            => @$certData->validFrom->format('Y-m-d H:i:s'),
            'certNotAfter'             => @$certData->validTo->format('Y-m-d H:i:s'),
            'certSubject'              => Cert::formatCertAttributes(@$certData->subjectName),
            'certFingerprint'          => @$certData->fingerprint,
        ]);
    }

    public static function buildForAutobot($autobotId, User $signerUser)
    {
        $autobot = static::findAutobot($autobotId, $signerUser);
        $ownerName = $autobot->controller ? $autobot->controller->fullName : null;
        $certData = X509FileModel::loadData($autobot->certificate);

        $form = new static([
            'autobotId'                => $autobotId,
            'participantName'          => static::getParticipantNameByTerminalId($autobot->controller->terminal->terminalId),
            'certOwnerFullName'        => $ownerName,
            'certOwnerRole'            => Cert::ROLE_SIGNER_BOT,
            'certOwnerPassportCountry' => 'РФ',
            'certIssuer'               => Cert::formatCertAttributes(@$certData->issuerName),
            'certNotBefore'            => @$certData->validFrom->format('Y-m-d H:i:s'),
            'certNotAfter'             => @$certData->validTo->format('Y-m-d H:i:s'),
            'certSubject'              => Cert::formatCertAttributes(@$certData->subjectName),
            'certFingerprint'          => @$certData->fingerprint,
        ]);
        $form->loadControllerActData();
        return $form;
    }

    public static function buildForUserAuthCert($userAuthCertId)
    {
        $userAuthCert = UserAuthCert::findOne($userAuthCertId);
        $certData = X509FileModel::loadData($userAuthCert->certificate);

        $form = new static([
            'userAuthCertId'           => $userAuthCertId,
            'participantName'          => null,
            'certOwnerFullName'        => $userAuthCert->user->getName(),
            'certOwnerRole'            => Cert::ROLE_SIGNER,
            'certOwnerPassportCountry' => 'РФ',
            'certIssuer'               => Cert::formatCertAttributes(@$certData->issuerName),
            'certNotBefore'            => @$certData->validFrom->format('Y-m-d H:i:s'),
            'certNotAfter'             => @$certData->validTo->format('Y-m-d H:i:s'),
            'certSubject'              => Cert::formatCertAttributes(@$certData->subjectName),
            'certFingerprint'          => @$certData->fingerprint,
        ]);
        return $form;
    }

    public function getActFileContent()
    {
        return $this->actFileContent;
    }

    public function getActFileName()
    {
        return $this->actFileName;
    }

    public function generateAct()
    {
        $tempDirPath = Yii::getAlias('@temp/cert-acknowledgement-acts');
        $docxTemplate = new DocxTemplate($tempDirPath);

        $actFileContent = null;
        try {
            $this->actFileContent = $docxTemplate->render($this->getDocxTemplatePath(), $this->getDocxTemplateData());
            $this->actFileName = $this->generateActFileName();
        } catch (Exception $exception) {
            Yii::warning("Failed to create act file, caused by: {$exception->getMessage()}");
            return false;
        }
        return true;
    }

    public function saveControllerActData(): void
    {
        $autobot = Autobot::findOne($this->autobotId);
        $controller = $autobot->controller;
        $controllerActData = $controller->certAcknowledgementActData ?: new ControllerCertAcknowledgementActData(['controllerId' => $controller->id]);

        $controllerActData->setAttributes([
            'agreementType'                  => $this->agreementType,
            'agreementNumber'                => $this->agreementNumber,
            'agreementDate'                  => DateHelper::convertFormat($this->agreementDate, 'd.m.Y', 'Y-m-d'),
            'signerFullName'                 => $this->signerFullName,
            'signerPosition'                 => $this->signerPosition,
            'signerAuthority'                => $this->signerAuthority,
            'certOwnerPosition'              => $this->certOwnerPosition,
            'certOwnerPassportCountry'       => $this->certOwnerPassportCountry,
            'certOwnerPassportSeries'        => $this->certOwnerPassportSeries,
            'certOwnerPassportNumber'        => $this->certOwnerPassportNumber,
            'certOwnerPassportAuthorityCode' => $this->certOwnerPassportAuthorityCode,
            'certOwnerPassportAuthority'     => $this->certOwnerPassportAuthority,
            'certOwnerPassportIssueDate'     => DateHelper::convertFormat($this->certOwnerPassportIssueDate, 'd.m.Y', 'Y-m-d'),
        ]);
        // Сохранить модель в БД
        $isSaved = $controllerActData->save();
        if (!$isSaved) {
            Yii::info('Failed to save controller act data, errors: ' . var_export($controllerActData->getErrors(), true));
        }
    }

    private function loadControllerActData(): void
    {
        $autobot = Autobot::findOne($this->autobotId);
        $controllerActData = $autobot->controller->certAcknowledgementActData;
        if ($controllerActData === null) {
            return;
        }

        $updateAttribute = function ($attribute, $newValue) {
            if ($newValue !== null && $newValue !== '') {
                $this->$attribute = $newValue;
            }
        };

        $stringAttributes = [
            'agreementType', 'agreementNumber', 'signerFullName', 'signerPosition', 'signerAuthority',
            'certOwnerPosition', 'certOwnerPassportCountry', 'certOwnerPassportSeries',
            'certOwnerPassportNumber', 'certOwnerPassportAuthorityCode', 'certOwnerPassportAuthority',
        ];
        foreach ($stringAttributes as $attribute) {
            $updateAttribute($attribute, $controllerActData->$attribute);
        }

        foreach (['agreementDate', 'certOwnerPassportIssueDate'] as $attribute) {
            if ($controllerActData->$attribute) {
                $updateAttribute($attribute, DateHelper::convertFormat($controllerActData->$attribute, 'Y-m-d', 'd.m.Y'));
            }
        }
    }

    private function generateActFileName()
    {
        $name = "Акт о признании ЭП, {$this->certOwnerFullName}, {$this->certFingerprint}.docx";
        return preg_replace('/[^a-zа-я0-9\-_\. @]+/iu', '', $name);
    }

    private function getDocxTemplateFileName()
    {
        if ($this->agreementType === static::AGREEMENT_TYPE_DBO) {
            return $this->certOwnerRole == Cert::ROLE_SIGNER ? 'dbo-signer.docx' : 'dbo-controller.docx';
        }
        return 'ito-controller.docx';
    }

    private function getDocxTemplatePath()
    {
        return Yii::getAlias('@common/modules/certManager/resources/cert-acknowledgement-acts-templates/') . $this->getDocxTemplateFileName();
    }

    private function getDocxTemplateData()
    {
        return array_merge(
            $this->getAttributes(),
            [
                'signerShortName'    => static::shortenFullName($this->signerFullName),
                'certOwnerShortName' => static::shortenFullName($this->certOwnerFullName),
                'certIssuer'         => preg_replace('/\/+/', "\r\n", $this->certIssuer),
                'certSubject'        => preg_replace('/\/+/', "\r\n", $this->certSubject),
                'participantName'    => $this->participantName != null ? $this->participantName : $this->participantFullName
            ]
        );
    }

    private static function shortenFullName($name)
    {
        $nameParts = preg_split('/\s+/', $name);
        if (count($nameParts) < 3) {
            return $name;
        }

        $shortenNamePart = function ($string) {
            return preg_replace('/^(.).*$/u', '$1.', $string);
        };

        return array_reduce(
            array_keys($nameParts),
            function ($carry, $index) use ($nameParts, $shortenNamePart) {
                $shouldShorten = $index >= count($nameParts) - 2;
                $isLast = $index === count($nameParts);

                $part = $shouldShorten
                    ? $shortenNamePart($nameParts[$index])
                    : $nameParts[$index];

                return $carry . $part . ($isLast || !$shouldShorten ? ' ' : '');
            },
            ''
        );
    }

    private static function getParticipantNameByBic($bic)
    {
        $participant = BICDirParticipant::find()->where(['participantBIC' => $bic])->one();
        return $participant == null ? null : $participant->name;
    }

    private static function getParticipantNameByTerminalId($terminalId)
    {
        $participantBic = TerminalId::extractParticipantId($terminalId);
        return static::getParticipantNameByBic($participantBic);
    }

    private static function findAutobot($id, User $userIdentity)
    {
        $autobot = Autobot::findOne($id);
        if ($autobot == null) {
            return null;
        }
        if ($userIdentity->role != User::ROLE_ADMIN) {
            $terminalsList = UserTerminal::getUserTerminalIds($userIdentity->id);

            if (!in_array($autobot->controller->terminal->terminalId, $terminalsList)) {
                return null;
            }
        }

        return $autobot;
    }

}
