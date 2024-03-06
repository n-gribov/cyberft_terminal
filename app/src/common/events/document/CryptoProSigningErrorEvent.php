<?php
namespace common\events\document;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class CryptoProSigningErrorEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function getLabel()
    {
        return Yii::t('document', 'CryptoPro signing error');
    }
}