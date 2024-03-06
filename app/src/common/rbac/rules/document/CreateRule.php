<?php

namespace common\rbac\rules\document;

use common\document\DocumentPermission;

class CreateRule extends Rule
{
    public $name = 'documentCreateRule';

    protected function getPermissionCode()
    {
        return DocumentPermission::CREATE;
    }
}
