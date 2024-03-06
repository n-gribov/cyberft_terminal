<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\helpers\Html;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\MonitorLogAR;

class EditCertEvent extends BaseEvent
{
    public $id;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['id','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Редактирование сертификата';
    }

    public function getLabel()
    {
        $certLink = Html::a($this->id, ['/certManager/cert/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit certificate {id}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $certLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}