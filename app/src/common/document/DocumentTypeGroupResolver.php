<?php

namespace common\document;

class DocumentTypeGroupResolver
{
    /**
     * @var DocumentTypeGroup[]
     */
    private $documentTypeGroups;

    public function __construct(array $documentTypeGroups)
    {
        $this->documentTypeGroups = $documentTypeGroups;
    }

    public function resolve(string $documentType, array $extModelAttributes): ?DocumentTypeGroup
    {
        foreach ($this->documentTypeGroups as $documentTypeGroup) {
            if ($documentTypeGroup->matchesDocument($documentType, $extModelAttributes)) {
                return $documentTypeGroup;
            }
        }
        return null;
    }
}
