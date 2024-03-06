<?php
namespace addons\ISO20022\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use common\states\out\SendingState;

class ISO20022OutState extends BaseDocumentState
{
    public $filePath;
    public $origin = Document::ORIGIN_FILE;
    public $sender = null;
    public $receiver = null;
    public $originalFilename;
    public $initialType = null;
    public $xmlContent = null;

    public $logCategory = 'ISO20022';

    protected $_steps = [
        'decide' => null, // step decider
        'prepare' => 'addons\ISO20022\states\out\ISO20022PrepareStep',
        'create' => 'common\states\out\DocumentCreateStep',
        'extCreate' => 'common\states\ExtModelCreateStep',
        //'common\states\UnlockStep',
        'pain001chk' => 'addons\ISO20022\states\out\Pain001CheckStep',
        'pain002chk' => 'addons\ISO20022\states\out\Pain002CheckStep',
        'camt053chk' => 'addons\ISO20022\states\out\Camt053CheckStep',
        'camt054chk' => 'addons\ISO20022\states\out\Camt054CheckStep',
        'cpsign' => 'addons\ISO20022\states\out\CryptoProSignStep',
    ];

    public function decideState()
    {
        return new SendingState();
    }

    public function decideStep()
    {
        if ($this->initialType == 'PaymentOrder' || $this->initialType == 'PaymentRegister') {
            return 'addons\ISO20022\states\out\PaymentOrderConvertStep';
        }

        return 'common\states\DummyStep';
    }

}