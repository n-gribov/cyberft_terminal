<?php

namespace addons\edm\models\IBank\common;

abstract class IBankDocument
{
    protected $type;
    protected $data = [];
    protected $fileName;
    protected $senderInn;
    protected $senderBik;

    public function __construct(string $type, array $data, string $fileName = null)
    {
        $this->type = $type;
        $this->data = $data;
        $this->fileName = $fileName;
        $this->parseFileName();
    }

    abstract public function getSenderAccountNumber();
    abstract public function getRemoteClientId();

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSenderInn()
    {
        return $this->senderInn;
    }

    public function getSenderBik()
    {
        return $this->senderBik;
    }

    private function parseFileName()
    {
        $pattern = '/^(\d{10,12})_(\d{9})_.+\.\w+$/';
        if (preg_match($pattern, $this->fileName, $matches) === 1) {
            $this->senderInn = $matches[1];
            $this->senderBik = $matches[2];
        }
    }
}
