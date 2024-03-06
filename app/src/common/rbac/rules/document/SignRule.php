<?php

namespace common\rbac\rules\document;

use common\document\Document;
use common\document\DocumentPermission;

class SignRule extends Rule
{
    public $name = 'documentSignRule';

    protected function getPermissionCode()
    {
        return DocumentPermission::SIGN;
    }

    protected function isAllowedForDocument(Document $document): bool
    {
        $extModel = $document->extModel;
        return $extModel !== null && $extModel->canBeSignedByUser(\Yii::$app->user->identity, $document);
    }
}
