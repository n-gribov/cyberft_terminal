<?php

namespace common\components\processingApi\models\directory;

class Directory
{
    private $version;
    private $isModified;
    private $participants;

    public function __construct(bool $isModified, string $version, ?string $responsePayload = null)
    {
        if ($isModified) {
            if ($responsePayload === null) {
                throw new \InvalidArgumentException('Response payload is required');
            }
            $this->participants = $this->parseDirectory($responsePayload);
        }
        $this->version = $version;
        $this->isModified = $isModified;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function isModified(): bool
    {
        return $this->isModified;
    }

    /**
     * @return Participant[]
     */
    public function getParticipants(): array
    {
        if (!$this->isModified()) {
            throw new \Exception('Directory in not modified and has no updated data');
        }
        return $this->participants;
    }

    private function parseDirectory(string $jsonContent): array
    {
        $data = \GuzzleHttp\json_decode($jsonContent, true);
        return array_map(
            function ($participantData) {
                return new Participant($participantData);
            },
            $data
        );
    }
}
