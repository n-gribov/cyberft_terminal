<?php
namespace common\states;

use common\states\BaseDocumentStep;

class DummyStep extends BaseDocumentStep
{
    public $name = 'dummy';

    public function run()
    {
        return true;
    }

}