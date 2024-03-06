<?php

namespace common\base\traits;

use common\document\Document;
use Yii;
use yii\web\ForbiddenHttpException;

trait AuthorizesDocumentPermission
{
    protected function authorizeDocumentPermission(string $serviceId, $permissionCodes, Document $document): void
    {
        if (!is_array($permissionCodes)) {
            $permissionCodes = [$permissionCodes];
        }
        foreach ($permissionCodes as $permissionCode) {
            $isAllowed = Yii::$app->user->can(
                $permissionCode,
                [
                    'serviceId' => $serviceId,
                    'document' => $document,
                ]
            );
            if ($isAllowed) {
                return;
            }
        }

        Yii::info("Access to document {$document->id} is forbidden, required permissions: " . implode(', ', $permissionCodes));
        throw new ForbiddenHttpException();
    }
}
