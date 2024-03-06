<?php

namespace addons\edm\jobs\ExportJob;

class ExportResult
{
    private const STATUS_EXPORTED = 'exported';
    private const STATUS_NOT_REQUIRED = 'notRequired';
    private const STATUS_FAILED = 'failed';

    private $status;
    private $filePath;

    private function __construct(string $status, string $filePath = null)
    {
        $this->status = $status;
        $this->filePath = $filePath;
    }

    public static function exported($filePath = null): self
    {
        return new self(self::STATUS_EXPORTED, $filePath);
    }

    public static function notRequired(): self
    {
        return new self(self::STATUS_NOT_REQUIRED);
    }

    public static function failed(): self
    {
        return new self(self::STATUS_FAILED);
    }

    public function isExported(): bool
    {
        return $this->status === self::STATUS_EXPORTED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
    
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }
}
