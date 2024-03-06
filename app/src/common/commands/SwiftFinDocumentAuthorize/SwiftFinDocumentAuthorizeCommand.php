<?php
namespace common\commands\SwiftFinDocumentAuthorize;

use common\commands\BaseCommand;

class SwiftFinDocumentAuthorizeCommand extends BaseCommand
{
    public $extStatus;

    public function getAcceptsCount()
    {
        return 0;
    }
}