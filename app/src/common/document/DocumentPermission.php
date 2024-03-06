<?php

namespace common\document;

class DocumentPermission
{
    const VIEW = 'documentView';
    const CREATE = 'documentCreate';
    const DELETE = 'documentDelete';
    const SIGN = 'documentSign';

    public static function all()
    {
        return [
            static::VIEW,
            static::CREATE,
            static::DELETE,
            static::SIGN,
        ];
    }
}
