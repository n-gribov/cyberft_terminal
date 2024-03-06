<?php

namespace common\components\processingApi\models\directory;

class Certificate
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';

    private $terminalAddress;
    private $fingerprint;
    private $body;
    private $status;
    /**
     * @var \DateTimeImmutable
     */
    private $startDate;
    /**
     * @var \DateTimeImmutable
     */
    private $endDate;
    private $ownerName;

    public function __construct(array $data)
    {
        $this->terminalAddress = $data['terminal'];
        $this->fingerprint = $data['fingerprint'];
        $this->body = $data['certificate'];
        $this->status = $data['status'];
        $this->startDate = new \DateTimeImmutable($data['startDate']);
        $this->endDate = new \DateTimeImmutable($data['endDate']);
        $this->ownerName = $data['ownerName'];
    }

    public function getTerminalAddress()
    {
        return $this->terminalAddress;
    }

    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getOwnerName()
    {
        return $this->ownerName;
    }
}
