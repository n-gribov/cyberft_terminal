<?php

namespace common\exceptions;

use Throwable;

class InvalidImportedDocumentException extends \DomainException
{
    private $documentNumber;

    public function __construct($message = "", $documentNumber = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->documentNumber = $documentNumber;
    }

    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }
}
