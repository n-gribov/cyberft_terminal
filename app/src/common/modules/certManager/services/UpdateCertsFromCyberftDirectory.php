<?php

namespace common\modules\certManager\services;

use common\components\processingApi\models\directory\Certificate;
use common\components\processingApi\models\directory\Directory;
use common\components\processingApi\models\directory\Participant;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use Yii;

class UpdateCertsFromCyberftDirectory
{
    private const DB_DATE_FORMAT = 'Y-m-d H:i:s';

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
        $certificatesFromDirectory = $this->getCertificatesFromDirectory();

        foreach ($certificatesFromDirectory as $certificate) {
            try {
                $this->createOrUpdateCert($certificate);
            } catch (\Exception $exception) {
                Yii::info("Failed import certificate {$certificate->getTerminalAddress()}-{$certificate->getFingerprint()}, caused by: $exception");
            }
        }

        $this->deleteOldCerts($certificatesFromDirectory);
    }

    /**
     * @return Certificate[]
     */
    private function getCertificatesFromDirectory(): array
    {
        return array_merge(
            ...array_map(
                function (Participant $participant) {
                    return $participant->getControllersCertificates();
                },
                $this->directory->getParticipants()
            )
        );
    }

    private function createOrUpdateCert(Certificate $certificateFromDirectory): void
    {
        $cert = Cert::findByAddress($certificateFromDirectory->getTerminalAddress(), $certificateFromDirectory->getFingerprint(), null);
        if ($cert === null) {
            $cert = new Cert([
                'terminalId'  => $certificateFromDirectory->getTerminalAddress(),
                'fingerprint' => $certificateFromDirectory->getFingerprint(),
            ]);
        }
        $cert->setScenario(Cert::SCENARIO_AUTO_IMPORT);
        $cert->autoUpdate = 1;
        $cert->body = $certificateFromDirectory->getBody();
        $cert->role = Cert::ROLE_SIGNER_BOT;
        $cert->status = $certificateFromDirectory->getStatus() === Certificate::STATUS_ACTIVE ? Cert::STATUS_C10 : Cert::STATUS_C12;
        $cert->setFullName($certificateFromDirectory->getOwnerName());
        $x509 = X509FileModel::loadData($cert->body);
        $cert->validFrom = $x509->getValidFrom()->format(self::DB_DATE_FORMAT);
        $cert->validBefore = $x509->getValidTo()->format(self::DB_DATE_FORMAT);
        $cert->useBefore = $certificateFromDirectory->getEndDate()->format(self::DB_DATE_FORMAT);
        $isSaved = $cert->save();
        if (!$isSaved) {
            throw new \Exception('Cannot save certificate to database, errors: ' . var_export($cert->getErrors(), true));
        }
    }

    private function deleteOldCerts(array $certificatesFromDirectory): void
    {
        $keyCodesToRetain = array_map(
            function (Certificate $certificate) {
                return "{$certificate->getTerminalAddress()}-{$certificate->getFingerprint()}";
            },
            $certificatesFromDirectory
        );
        $certs = Cert::find()->where(['autoUpdate' => true])->all();
        /** @var Cert $cert */
        foreach ($certs as $cert) {
            $keyCode = "{$cert->terminalId}-{$cert->fingerprint}";
            if (!in_array($keyCode, $keyCodesToRetain)) {
                $cert->delete();
            }
        }
    }
}
