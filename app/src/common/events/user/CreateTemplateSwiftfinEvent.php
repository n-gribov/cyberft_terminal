<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class CreateTemplateSwiftfinEvent extends BaseEvent
{
    public $swiftfinTemplateId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['swiftfinTemplateId','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Создание шаблона SWIFTFIN';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {

        $templateLink = Html::a($this->swiftfinTemplateId, ['/swiftfin/templates/view', 'id' => $this->swiftfinTemplateId]);

        return Yii::t('monitor/events',
            '{initiator} {user} create document SWIFTFIN template {template}',
            [
                'user' => $this->getUserData()['userLink'],
                'template' => $templateLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}