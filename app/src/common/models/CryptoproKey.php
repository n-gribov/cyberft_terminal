<?php

namespace common\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id        Row ID
 * @property string  $serialNumber Certificate Serial
 * @property string  $keyId     Certificate hash
 * @property string  $ownerName Owner name
 * @property string  $certData  Certificate body data
 * @property string  $password  Key password
 * @property string  $status    Key status
 * @property integer $userId    User ID
 * @property integer $active    Active
 * @property datetime $expireDate Certificate expire date
 * @property string  $algo      Algo
 */
class CryptoproKey extends ActiveRecord
{
    const STATUS_NOT_READY = 'notReady';
    const STATUS_READY = 'ready';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cryptoproKey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certData'], 'string'],
            [['keyId'], 'required'],
            [['terminalId'], 'integer'],
            ['terminalId', 'exist', 'targetClass' => 'common\models\Terminal', 'targetAttribute' => 'id'],
            [['keyId', 'ownerName'], 'string', 'max' => 255],
            ['serialNumber', 'string'],
            [['password', 'status'], 'string'],
            [['active', 'userId'], 'integer'],
            [['expireDate', 'algo'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_NOT_READY],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app/fileact', 'ID'),
            'keyId'     => Yii::t('app/fileact', 'Fingerprint'),
            'serialNumber' => Yii::t('app/fileact', 'Serial Number'),
            'ownerName' => Yii::t('app/cert', 'Owner Name'),
            'certData'  => Yii::t('app/fileact', 'Certificate data'),
            'active'    => Yii::t('app/fileact', 'Activate'),
            'userId'    => Yii::t('app/fileact', 'User ID'),
            'status'    => Yii::t('app/fileact', 'Status'),
            'expireDate' => Yii::t('app/fileact', 'Expire date'),
            'password'  => Yii::t('app/fileact', 'Password'),
            'terminalId'  => Yii::t('app/terminal', 'Terminal address'),
        ];
    }

    /**
     * Get status labels list
     *
     * @return array Return list of status labels
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_READY     => Yii::t('app/fileact', 'Ready to use'),
            self::STATUS_NOT_READY => Yii::t('app/fileact', 'Not ready'),
        ];
    }

    /**
     * Список статусов активности сертификата
     * @return array
     */
    public static function getActiveLabels()
    {
        return [
            self::STATUS_READY        => Yii::t('app/fileact', 'Active'),
            self::STATUS_NOT_READY   => Yii::t('app/fileact', 'Inactive'),
        ];
    }

    /**
     * Get status label
     *
     * @return string Return localization status label
     */
    public function getStatusLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getStatusLabels()))
            ? self::getStatusLabels()[$this->status]
            : $this->status;
    }

    /**
     * Получение наименование активен/не активен для статуса сертификата
     * @return string
     */

    public function getActiveLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getActiveLabels()))
            ? self::getActiveLabels()[$this->status]
            : $this->status;
    }

    /**
     * Get user list
     *
     * @return array
     */
    public function getUserList()
    {
        $usersList = [];

        $users = User::findByRole([
                USER::ROLE_USER,
                //USER::ROLE_EDM_OPERATOR,
                //USER::ROLE_EDM_CONTROLLER
        ]);

        foreach ($users as $user) {
            $usersList[$user->id] = $user->email;
        }

        return $usersList;
    }

    public function getTerminals()
    {
        return $this->hasMany(Terminal::className(), ['id' => 'terminalId'])->viaTable('cryptoproKeyTerminal', ['keyId' => 'id']);
    }

    public function getBeneficiaries()
    {
	return CryptoproKeyBeneficiary::findAll(['keyId' => $this->id]);
    }

    /**
     * Связь с таблицей пользователей для более удобной фильтрации
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public static function findByTerminalId($terminalId, $fingerprint = null)
    {
		return static::find()
		    ->innerJoin('`cryptoproKeyTerminal` `kt`',
			    [
				'and',
				'`kt`.`keyId` = `id`',
				['`kt`.`terminalId`' => $terminalId],
			    ])
		    ->andWhere(['status' => static::STATUS_READY])
		    ->andWhere(['active' => 1])
            ->andFilterWhere([static::tableName() . '.keyId' => $fingerprint])
		    ->all();

    }

}
