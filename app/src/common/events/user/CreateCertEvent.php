<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\helpers\Html;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\MonitorLogAR;

class CreateCertEvent extends BaseEvent
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
        return 'Добавление Сертификата';
    }

    public function getLabel()
    {
        $cert = Cert::findOne($this->id);

        if (empty($cert)) {
            $certLink = $this->id;
        } else {
            $certLink = Html::a($cert->certId, ['/certManager/cert/view', 'id' => $cert->id]);
        }

        return Yii::t('monitor/events',
            '{initiator} {user} create certificate {id}',
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