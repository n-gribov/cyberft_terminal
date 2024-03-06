<?php

namespace common\models\sbbolxml\digest;

class DigestEntryConfig
{
    private $path;
    private $format;

    public function __construct($entrySpec, $pathPrefix = '')
    {
        $specParts = explode(':', $entrySpec, 2);
        $this->path = $pathPrefix . $specParts[0];
        $this->format = $specParts[1] ?? null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
