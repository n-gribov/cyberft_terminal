<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\base\interfaces\ServiceUserExtInterface;
use common\models\User;

/**
 * @property array $permissions Mapped JSON attribute stored in permissionsData
 */

class CommonUserExt extends ActiveRecord implements ServiceUserExtInterface
{
    private $_user;

    /**
     * Сервисы модели
     * @return string
     */
    const CERTIFICATES = '10';
    const DOCUMENTATION_WIDGETS = '20';
    const IMPORT_ERRORS_JOURNAL = '30';

    /**
     * Список сервисов
     * @return string[]
     */
    public static function getServices()
    {
        return [
            self::CERTIFICATES,
            self::DOCUMENTATION_WIDGETS,
            self::IMPORT_ERRORS_JOURNAL
        ];
    }

    /**
     * Заголовки сервисов
     * @return array
     */

    public static function getServiceLabels()
    {
        return [
            self::CERTIFICATES => Yii::t('app/user', 'Certificates management'),
            self::DOCUMENTATION_WIDGETS => Yii::t('app/user', 'Documentation widgets management'),
            self::IMPORT_ERRORS_JOURNAL => Yii::t('app/user', 'View import documents errors journal')
        ];
    }

    /**
     * Получение заголовка сервиса
     * @return string
     */
    public static function getServiceLabel($service)
    {
        return (!is_null($service) && array_key_exists($service, self::getServiceLabels()))
            ? self::getServiceLabels()[$service]
            : $service;
    }

    public static function tableName()
    {
        return 'common_UserExt';
    }

    function behaviors()
    {
        return [
            [
                'class'=>  \common\behaviors\JsonArrayBehavior::className(),
                'attributes'=>[
                    'settings' => 'data'
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [['userId', 'type'], 'required'],
            [['canAccess'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app', 'ID'),
            'userId'    => Yii::t('app', 'User ID'),
            'canAccess' => Yii::t('doc', 'Access to service'),
        ];
    }

    public function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = $this->hasOne('common\models\User', ['id' => 'userId']);
        }

        return $this->_user;
    }

    public function isAllowedAccess()
    {
        return (bool) $this->canAccess;
    }

    /**
     * Метод проверяет наличие дополнительных настроек у сервиса
     * @return bool
     */
    public function hasSettings()
    {
        return false;
    }

    /**
     * Заголовки настроек сервисов
     */
    public function settingsLabels()
    {
        return [];
    }

}
