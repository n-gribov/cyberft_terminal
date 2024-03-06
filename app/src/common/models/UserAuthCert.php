<?php

namespace common\models;

use addons\edm\models\UserAuthCertBeneficiary;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use DateTime;

/**
 * @property integer $id          Row ID
 * @property integer $userId      User ID
 * @property string  $name        Certificate name
 * @property string  $certificate Certificate body
 * @property string  $fingerprint Certificate fingerprint
 * @property string  $expiryDate
 * @property array   $beneficiaryList
 * @property string  $status
 * @property-read User $user
 */
class UserAuthCert extends ActiveRecord
{
    private $_certRequisites;

    public static function tableName()
	{
		return 'user_auth_cert';
	}

	public function rules()
	{
		return [
			[['userId', 'fingerprint', 'certificate'], 'required'],
			['userId', 'integer'],
			[['certificate', 'status'], 'string'],
			['fingerprint', 'string', 'max' => 64],
            [
                'fingerprint',
                'unique',
                'targetAttribute' => ['fingerprint', 'userId'],
                'message' => Yii::t('app/user', 'Certificate already exists')
            ],
		];
	}

	public function attributeLabels()
	{
		return [
            'id'           => Yii::t('app/user', 'ID'),
            'userId'       => Yii::t('app/user', 'User ID'),
            'expiryDate'   => Yii::t('app/cert', 'Valid before'),
            'certificate'  => Yii::t('app/user', 'Certificate'),
            'fingerprint'  => Yii::t('app', 'Fingerprint'),
            'serialNumber' => Yii::t('app/cert', 'Serial Number'),
            'subject'      => Yii::t('app/cert', 'Subject'),
            'issuer'       => Yii::t('app/cert', 'Issuer Name'),
            'validFrom'    => Yii::t('app/cert', 'Valid from'),
            'validTo'      => Yii::t('app/cert', 'Valid before'),
            'status'       => Yii::t('app', 'Status'),
            'signatureTypeLN' => Yii::t('app/cert', 'Signature Algorithm'),
        ];
    }

    public function getSerialNumber()
    {
        $certRequisites = $this->getCertRequisites();
        return $certRequisites['serialNumber'];
    }

    public function getSubject($raw = false)
    {
        $certRequisites = $this->getCertRequisites();
        if ($raw) {
            return $certRequisites['subject'];
        }
        $subject = '';
        foreach ($certRequisites['subject'] as $key => $value) {
            $subject = $subject . $key . ' = ' . $value . "\n";
        }
        return $subject;
    }

    public function getIssuer($raw = false)
    {
        $certRequisites = $this->getCertRequisites();
        if ($raw) {
            return $certRequisites['issuer'];
        }
        $issuer = '';
        foreach ($certRequisites['issuer'] as $key => $value) {
            $issuer = $issuer . $key . ' = ' . $value . "\n";
        }
        return $issuer;
    }

    public function getValidFrom()
    {
        $certRequisites = $this->getCertRequisites();
        return $certRequisites['validFrom_time_t'];
    }

    public function getValidTo()
    {
        $certRequisites = $this->getCertRequisites();
        return $certRequisites['validTo_time_t'];
    }

    public function getSignatureTypeLN()
    {
        $certRequisites = $this->getCertRequisites();
        return $certRequisites['signatureTypeLN'];
    }

    public function getBeneficiaryList()
    {
        $beneficiaryList = [];

        $beneficiaries = UserAuthCertBeneficiary::findAll(['keyId' => $this->id]);

        if (count($beneficiaries) == 0) {
            return $beneficiaryList;
        }

        foreach ($beneficiaries as $beneficiary) {
            $truncatedId = substr_replace($beneficiary->terminalId, '', -4, 1);
            $bicDirParticipant = BICDirParticipant::findOne(['participantBIC' => $truncatedId]);
            $participantName = $bicDirParticipant ? $bicDirParticipant->name : '';
            $beneficiaryList[] = $participantName . ' (' . $beneficiary->terminalId . ')';
        }

        return $beneficiaryList;
    }

    /**
     * Get user for this certificate
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function load($data, $formName = null)
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

        $fingerprint = Yii::$app->cryptography->getFingerprint($this->certificate);
        if ($fingerprint !== false) {
            $this->fingerprint = $fingerprint;
            return true;
        }
        return false;
    }

    public function getMappedAttributes($map, $attributes)
    {
        $out = [];
        foreach ($map as $key) {
            if (isset($attributes[$key])) {
                $out[] = $key . ':' . $attributes[$key];
                unset($attributes[$key]);
            }
        }
        foreach($attributes as $key => $value) {
            $out[] = $key . ':' . $value;
        }

        return $out;
    }

    private function getCertRequisites()
    {
        if (empty($this->_certRequisites)) {
            $this->_certRequisites = openssl_x509_parse($this->certificate);
        }

        return $this->_certRequisites;
    }

    public function isExpiringSoon($days = 30)
    {
        $dateNow = new DateTime();
        $dateExpiration = new DateTime($this->expiryDate);
        $interval = $dateNow->diff($dateExpiration);

        return $interval->days <= $days;
    }

    public function getIsActive()
    {
        return strtotime($this->expiryDate) > mktime();
    }

}

