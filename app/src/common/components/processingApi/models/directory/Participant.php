<?php

namespace common\components\processingApi\models\directory;

class Participant
{
    public const STATUS_ACTIVE  = 'active';
    public const STATUS_BLOCKED = 'blocked';
    public const TYPE_PROCESSING = 'processing';
    public const TYPE_PARTICIPANT = 'participant';

    private $name;
    private $internationalName;
    private $type;
    private $swiftCode;
    private $processingSwiftCode;
    private $status;
    /**
     * @var Certificate[]
     */
    private $controllersCertificates;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->internationalName = $data['intName'];
        $this->type = $data['type'];
        $this->swiftCode = $data['code'];
        $this->processingSwiftCode = $data['processing'];
        $this->status = $data['status'];
        $this->controllersCertificates = array_map(
            function (array $certificateData) {
                return new Certificate($certificateData);
            },
            $data['controllersCertificates']
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInternationalName()
    {
        return $this->internationalName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSwiftCode()
    {
        return $this->swiftCode;
    }

    public function getProcessingSwiftCode()
    {
        return $this->processingSwiftCode;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Certificate[]
     */
    public function getControllersCertificates(): array
    {
        return $this->controllersCertificates;
    }
}
