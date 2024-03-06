<?php

namespace common\components\processingApi;


class HttpSignatureHeaders
{
    public const SIGNATURE = 'X-Signature';
    public const ALGORITHM = 'X-Signature-Algorithm';
    public const KEY_CODE = 'X-Signature-Key';
}
