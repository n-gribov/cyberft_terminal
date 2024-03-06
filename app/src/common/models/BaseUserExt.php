<?php

namespace common\models;

use common\document\DocumentPermission;
use Yii;
use yii\db\ActiveRecord;
use common\base\interfaces\ServiceUserExtInterface;

/**
 * @property int $id
 * @property int $canAccess
 * @property string $permissionsData Mapped JSON attribute stored in permissionsData
 * @property array $permissions
 */
class BaseUserExt extends ActiveRecord implements ServiceUserExtInterface
{
    /** @var \common\models\User */
    protected $_user;

    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['canAccess'], 'integer']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \common\behaviors\JsonArrayBehavior::className(),
                'attributes' => [
                    'permissions' => 'permissionsData'
                ]
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app', 'ID'),
            'userId'    => Yii::t('app', 'User ID'),
            'canAccess' => Yii::t('doc', 'Access to service')
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

    public function hasSettings()
    {
        return !empty($this->id);
    }

    public function permissionLabels()
    {
        return [
            DocumentPermission::VIEW   => Yii::t('app/user', 'View documents'),
            DocumentPermission::DELETE => Yii::t('app/user', 'Delete documents'),
            DocumentPermission::CREATE => Yii::t('app/user', 'Create documents'),
            DocumentPermission::SIGN   => Yii::t('app/user', 'Sign documents'),
        ];
    }

    public function getPermissionLabel($permission)
    {
        return in_array($permission, array_keys($this->permissionLabels()))
            ? $this->permissionLabels()[$permission]
            : $permission;
    }

    public function hasAdditionalSettings(): bool
    {
        return false;
    }

    public function hasPermission($permissionCode)
    {
        return is_array($this->permissions) && in_array($permissionCode, $this->permissions);
    }
}
