<?php
namespace common\rbac\rules\document;

use common\base\BaseBlock;
use common\document\Document;
use common\models\BaseUserExt;
use common\models\User;
use Yii;
use common\models\UserTerminal;
use yii\db\ActiveRecord;
use yii\rbac\Rule as YiiRule;

abstract class Rule extends YiiRule
{
    /**
     * @param int|string $userId
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     * Params:
     *  - serviceId - module id (required)
     *  - document - document instance (optional)
     *  - documentTypeGroup - optional document type group condition, may be one of:
     *      - document type group id - check permission for exact match
     *      - array of document type group ids - checks if user has permission for at least one of specified groups
     *      - '*' - checks if user has permission for at least one group
     */
    public function execute($userId, $item, $params)
    {
        $this->checkParams($params);

        if (!$this->hasAuthorizedUser() || !$this->userHasAccessToTerminals($userId)) {
            return false;
        }

        $serviceId = $params['serviceId'] ?? null;
        $document = $params['document'] ?? null;
        $documentTypeGroupCondition = $params['documentTypeGroup'] ?? null;

        try {
            return $this->isAllowed($serviceId, $document, $documentTypeGroupCondition);
        } catch (\Exception $exception) {
            Yii::error($exception);
            Yii::info("Got exception while checking rule {$this->name}: {$exception->getMessage()}");
        }

        return false;
    }

    abstract protected function getPermissionCode();

    protected function hasAuthorizedUser()
    {
        // Получить модель пользователя из активной сессии
        return !empty(Yii::$app->user) && !empty(Yii::$app->user->identity);
    }

    protected function userHasAccessToTerminals($userId)
    {
        return !empty(UserTerminal::getUserTerminalIds($userId)) || Yii::$app->user->identity->role === User::ROLE_ADMIN;
    }

    protected function isAllowed($serviceId, $document, $documentTypeGroupCondition): bool
    {
        if (!$serviceId) {
            throw new \Exception('Service id is not provided');
        }

        /** @var BaseUserExt $userExtModel */
        $userExtModel = \Yii::$app->user->identity->getServiceExtModel($serviceId);

        if (empty($userExtModel->permissions)) {
            return false;
        }

        if ($document && !$this->isAllowedForDocument($document)) {
            return false;
        }

        $isAllowedForAnyDocuments = in_array($this->getPermissionCode(), $userExtModel->permissions);
        if ($isAllowedForAnyDocuments) {
            return true;
        }

        if (!$document && !$documentTypeGroupCondition) {
            return false;
        }

        if (!$documentTypeGroupCondition) {
            $documentTypeGroup = $this->resolveDocumentTypeGroup($document, $serviceId);
            $documentTypeGroupCondition = $documentTypeGroup ? $documentTypeGroup->id : null;
            if (!$documentTypeGroupCondition) {
                return false;
            }
        }

        if ($documentTypeGroupCondition === '*') {
            return $this->hasPermissionForSomeDocumentTypeGroups($userExtModel);
        }

        $requiredDocumentTypeGroups = is_array($documentTypeGroupCondition)
            ? $documentTypeGroupCondition
            : [$documentTypeGroupCondition];

        return $this->hasPermissionForSomeOfDocumentTypeGroups($userExtModel, $requiredDocumentTypeGroups);
    }

    protected function resolveDocumentTypeGroup(Document $document, string $serviceId)
    {
        /** @var BaseBlock $module */
        $module = Yii::$app->getModule($serviceId);
        if (!$module) {
            throw new \Exception("Module $serviceId is not found");
        }

        $resolver = $module->createDocumentTypeGroupResolver();
        if (!$resolver) {
            return null;
        }
        /** @var ActiveRecord $extModel */
        $extModel = $document->extModel;
        if (!$extModel) {
            \Yii::info("Document {$document->id} has no ext model");
            return null;
        }
        return $resolver->resolve($document->type, $extModel->attributes);
    }

    protected function hasPermissionForSomeDocumentTypeGroups(BaseUserExt $userExtModel): bool
    {
        foreach ($userExtModel->permissions as $permissionValue) {
            $permissionMatches = strpos($permissionValue, "{$this->getPermissionCode()}:") === 0;
            if ($permissionMatches) {
                return true;
            }
        }

        return false;
    }

    protected function hasPermissionForSomeOfDocumentTypeGroups(BaseUserExt $userExtModel, array $groupsIds): bool
    {
        foreach ($groupsIds as $groupId) {
            $permissionValue = "{$this->getPermissionCode()}:$groupId";
            if (in_array($permissionValue, $userExtModel->permissions)) {
                return true;
            }
        }

        return false;
    }

    protected function checkParams(array $params)
    {
        $allowedParams = ['serviceId', 'documentTypeGroup', 'document'];
        $unsupportedParams = array_diff(array_keys($params), $allowedParams);
        if (count($unsupportedParams) > 0) {
            throw new \Exception('Got unsupported rule parameters: ' . implode(', ', $unsupportedParams));
        }
    }

    protected function isAllowedForDocument(Document $document): bool
    {
        return true;
    }
}
