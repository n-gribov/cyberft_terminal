<?php

namespace common\modules\participant\services;

use common\components\processingApi\models\directory\Directory;
use common\components\processingApi\models\directory\Participant;
use common\modules\participant\models\BICDirParticipant;
use Yii;

class UpdateParticipantsFromCyberftDirectory
{
    /**
     * @var Directory
     */
    private $directory;

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function run()
    {
        $changedCount = $this->updateParticipants();
        $deletedCount = $this->deleteOldParticipants();

        Yii::$app->monitoring->log(
            'participant:participantUpdated',
            null,
            null,
            [
                'startDate'   => time(),
                'count'       => $changedCount + $deletedCount,
                'requestType' => 'full',
            ]
        );
    }

    private function updateParticipants(): int
    {
        $changedCount = 0;
        foreach ($this->directory->getParticipants() as $participantFromDirectory) {
            try {
                $isChanged = $this->createOrUpdateParticipant($participantFromDirectory);
                if ($isChanged) {
                    $changedCount++;
                }
            } catch (\Exception $exception) {
                Yii::info("Failed import participant {$participantFromDirectory->getSwiftCode()}, caused by: $exception");
            }
        }
        return $changedCount;
    }

    private function createOrUpdateParticipant(Participant $participantFromDirectory): bool
    {
        $participant = BICDirParticipant::findOne(['participantBIC' => $participantFromDirectory->getSwiftCode()]);
        if ($participant === null) {
            $participant = new BICDirParticipant([
                'participantBIC' => $participantFromDirectory->getSwiftCode(),
            ]);
        }
        $participant->providerBIC = $participantFromDirectory->getProcessingSwiftCode();
        $participant->type = $this->getParticipantType($participantFromDirectory);
        $participant->name = $participantFromDirectory->getName();
        $participant->institutionName = $participantFromDirectory->getInternationalName();
        $participant->status = $this->getParticipantStatus($participantFromDirectory);
        $participant->blocked = $participantFromDirectory->getStatus() === Participant::STATUS_BLOCKED ? 1 : 0;
        if ($participant->isNewRecord && !$participant->documentFormatGroup) {
            $participant->documentFormatGroup = BICDirParticipant::getDefaultDocumentFormatGroup($participantFromDirectory->getSwiftCode());
        }
        $isDirty = !empty($participant->dirtyAttributes);
        $isSaved = $participant->save();
        if (!$isSaved) {
            throw new \Exception(
                'Cannot save participant to database'
                . ', data: ' . var_export($participant->attributes, true)
                . ', errors: ' . var_export($participant->getErrors(), true)
            );
        }
        return $isDirty;
    }

    private function getParticipantType(Participant $participantFromDirectory): int
    {
        switch ($participantFromDirectory->getType()) {
            case Participant::TYPE_PARTICIPANT:
                return BICDirParticipant::TYPE_PARTICIPANT;
            case Participant::TYPE_PROCESSING:
                return BICDirParticipant::TYPE_PROVIDER;
            default:
                throw new \Exception("Unsupported participant type: {$participantFromDirectory->getType()}");
        }
    }

    private function getParticipantStatus(Participant $participantFromDirectory): int
    {
        switch ($participantFromDirectory->getStatus()) {
            case Participant::STATUS_ACTIVE:
                return BICDirParticipant::STATUS_ACTIVE;
            case Participant::STATUS_BLOCKED:
                return BICDirParticipant::STATUS_BLOCKED;
            default:
                throw new \Exception("Unsupported participant status: {$participantFromDirectory->getStatus()}");
        }
    }

    private function deleteOldParticipants(): int
    {
        $codesToRetain = array_map(
            function (Participant $participant) {
                return $participant->getSwiftCode();
            },
            $this->directory->getParticipants()
        );
        return BICDirParticipant::deleteAll(['not in', 'participantBIC', $codesToRetain]);
    }
}
