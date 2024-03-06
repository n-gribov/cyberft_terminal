<?php

namespace common\components\processingApi\models;

class Certificate extends JsonSerializableObject
{
    public const ROLE_CONTROLLER = 'controller';
    public const STATUS_ACTIVE  = 'active';
    public const STATUS_BLOCKED = 'blocked';
    public const STATUS_PENDING = 'pending';

    public $terminal;
    public $code;
    public $certificate;
    public $status;
    public $startDate;
    public $endDate;
    public $ownerRole;
    public $ownerName;
    public $ownerEmail;
    public $ownerPhone;
    public $ownerPosition;

    protected function mapping(): array
    {
        return [
            'terminal'      => 'terminal',
            'code'          => 'code',
            'certificate'   => 'certificate',
            'status'        => 'status',
            'startDate'     => 'startDate',
            'endDate'       => 'endDate',
            'ownerRole'     => 'owner.role',
            'ownerName'     => 'owner.name',
            'ownerEmail'    => 'owner.email',
            'ownerPhone'    => 'owner.phone',
            'ownerPosition' => 'owner.position',
        ];
    }

    protected function propertyTypes(): array
    {
        return [
            'startDate' => static::PROPERTY_TYPE_DATE,
            'endDate'   => static::PROPERTY_TYPE_DATE,
        ];
    }
}
