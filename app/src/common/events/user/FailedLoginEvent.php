<?php
namespace common\events\user;

use common\models\User;
use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

/**
 * FailedLoginEvent is raised when user failed login attempts is higher
 * than predefined value
 *
 * @author a.nikolaenko
 *
 * @package modules
 * @subpackage monitor
 *
 * @property integer $failedCount Count of failed login attempts
 */
class FailedLoginEvent extends BaseEvent
{
    /**
     * @var integer $failedCount Count of failed login attempts
     */
    public $failedCount = 0;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['failedCount', 'integer']
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('app/user', 'Login failed');
    }


    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $user = User::findOne($this->entityId);

        $userName = !empty($user)? $user->name : $this->entityId;
        $userLink = Html::a($userName, ['/user/view', 'id' => $this->entityId]);

        return Yii::t('monitor/events',
                    'User {user} failed to login several times',
                    [
                        'user' => $userLink
                    ]
            );
    }
}