<?php
namespace addons\swiftfin\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use common\states\out\DocumentOutState;

class SwiftOutState extends BaseDocumentState
{
    public $filePath;
    public $origin = Document::ORIGIN_FILE;

    protected $_steps = [
        'prepare' => 'addons\swiftfin\states\out\SwiftPrepareStep',
    ];

    public function decideState()
    {
        return new DocumentOutState();
    }

}