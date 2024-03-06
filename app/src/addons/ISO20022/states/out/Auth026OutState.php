<?php
namespace addons\ISO20022\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use common\states\out\SendingState;

class Auth026OutState extends BaseDocumentState
{
    public $filePath;
    public $origin = Document::ORIGIN_FILE;
    public $sender = null;
    public $receiver = null;
    public $originalFilename;

    public $logCategory = 'ISO20022';

    protected $_steps = [
        'prepare' => 'addons\ISO20022\states\out\Auth026PrepareStep',
        'create' => 'common\states\out\DocumentCreateStep',
        'extCreate' => 'common\states\ExtModelCreateStep',
        'cpsign' => 'addons\ISO20022\states\out\CryptoProSignStep',
    ];

    public function decideState()
    {
        return new SendingState();
    }

}