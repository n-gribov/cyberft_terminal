<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\helpers\Html;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\MonitorLogAR;

class DeleteCertEvent extends BaseEvent
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
        return 'Удаление Сертификата';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} delete certificate {id}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $this->id,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}