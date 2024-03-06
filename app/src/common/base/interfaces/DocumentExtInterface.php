<?php
namespace common\base\interfaces;

use common\document\Document;
use common\models\User;

interface DocumentExtInterface
{
    public function loadContentModel($model);
    public function isDocumentDeletable(User $user = null);
    public function canBeSignedByUser(User $user, Document $document): bool;
}
