<?php

namespace addons\edm\models\IBank;

class IBankDocumentsPack extends \ArrayObject
{
    /** @var int */
    private $version;

    public function __construct(int $version, array $documents)
    {
        parent::__construct($documents);
        $this->version = $version;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
