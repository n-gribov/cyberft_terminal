<?php

namespace addons\SBBOL\models;

class ErrorRequestId
{
    const ERROR_REQUESTS_IDS = [
        '00000000-0000-0000-0000-000000000000' => 'Service is temporary unavailable',
        '00000000-0000-0000-0000-000000000001' => 'Invalid session id format',
        '00000000-0000-0000-0000-000000000002' => 'Invalid session id',
        '00000000-0000-0000-0000-000000000003' => 'Request is empty',
        '00000000-0000-0000-0000-000000000004' => 'List of requests is empty',
        '00000000-0000-0000-0000-000000000005' => 'Invalid user login',
        '00000000-0000-0000-0000-000000000006' => 'OrgId not found in message',
        '00000000-0000-0000-0000-000000000007' => 'Org not found',
        '00000000-0000-0000-0000-000000000008' => 'ContractAccessCode not found',
        '00000000-0000-0000-0000-000000000009' => 'Invalid org id format',
        '00000000-0000-0000-0000-000000000010' => 'Certificate not found',
        '00000000-0000-0000-0000-000000000011' => 'Size of request list exceed max size',
        '00000000-0000-0000-0000-000000000012' => 'Server access error',
        '00000000-0000-0000-0000-000000000013' => 'Wrong part parameter',
        '00000000-0000-0000-0000-000000000014' => 'Incorrect custom params',
        '00000000-0000-0000-0000-000000000015' => 'Incorrect access token',
        '00000000-0000-0000-0000-000000000016' => 'Incorrect SID2',
    ];

    public static function isErrorRequestId($requestId)
    {
        return array_key_exists($requestId, static::ERROR_REQUESTS_IDS);
    }

    public static function getErrorDescription($requestId)
    {
        return static::ERROR_REQUESTS_IDS[$requestId] ?? null;
    }
}