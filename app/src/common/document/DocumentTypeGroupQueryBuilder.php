<?php

namespace common\document;

use common\base\interfaces\BlockInterface;
use common\models\User;
use Yii;
use yii\db\Query;

class DocumentTypeGroupQueryBuilder
{
    /** @var string */
    private $serviceId;

    /** @var string[] */
    private $documentTypeGroupsIds;

    /** @var string */
    private $documentExtTableAlias;

    /** @var string */
    private $documentTableAlias;

    /** @var BlockInterface */
    private $module;

    public function __construct(
        string $serviceId,
        array $documentTypeGroupsIds,
        string $documentExtTableAlias = null,
        $documentTableAlias = 'document'
    ) {
        $this->serviceId = $serviceId;
        $this->documentTypeGroupsIds = $documentTypeGroupsIds;
        $this->documentExtTableAlias = $documentExtTableAlias;
        $this->documentTableAlias = $documentTableAlias;

        $this->module = Yii::$app->getModule($serviceId);
        if ($this->module === null) {
            throw new \Exception("Cannot get module for service $serviceId");
        }
    }

    public function applyToQuery(Query $query)
    {
        if (Yii::$app->user->identity->role !== User::ROLE_USER) {
            return;
        }

        $conditions = $this->createConditions();
        $this->applyConditions($conditions, $query);
    }

    private function createConditions(): array
    {
        $conditions = [];

        foreach ($this->documentTypeGroupsIds as $typeGroupId) {
            if (!$this->userHasDocumentTypeGroupAccess($typeGroupId)) {
                continue;
            }
            $typeGroup = $this->module->getDocumentTypeGroupById($typeGroupId);
            if ($typeGroup === null) {
                continue;
            }
            foreach ($typeGroup->types as $type) {
                $conditions[] = $this->createCondition($typeGroup, $type);
            }
        }

        return $conditions;
    }

    private function createCondition(DocumentTypeGroup $documentTypeGroup, string $documentType): array
    {
        $documentTypeColumn = $this->createColumnName($this->documentTableAlias, 'type');

        if (!$documentTypeGroup->hasDiscriminator($documentType)) {
            return [$documentTypeColumn => $documentType];
        }

        $discriminator = $documentTypeGroup->getDiscriminator($documentType);
        $discriminatorColumn = $this->createColumnName($this->documentExtTableAlias, $discriminator->attribute);
        return [
            'and',
            ['document.type' => $documentType],
            [$discriminatorColumn => $discriminator->value],
        ];
    }

    private function userHasDocumentTypeGroupAccess(string $typeGroupId): bool
    {
        return Yii::$app->user->can(
            DocumentPermission::VIEW,
            [
                'serviceId' => $this->serviceId,
                'documentTypeGroup' => $typeGroupId,
            ]
        );
    }

    private function applyConditions(array $conditions, Query $query)
    {
        if (count($conditions) > 0) {
            $query->andWhere(array_merge(['or'], $conditions));
        } else {
            $query->andWhere('1 = 0');
        }
    }

    private function createColumnName(string $tableAlias, string $column): string
    {
        return $tableAlias ? "$tableAlias.$column" : $column;
    }
}
