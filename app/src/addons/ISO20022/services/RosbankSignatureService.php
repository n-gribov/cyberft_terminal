<?php

namespace addons\ISO20022\services;

use addons\ISO20022\helpers\RosbankSignHelper;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\models\RosbankEnvelope;
use common\components\cryptography\drivers\DriverCryptoPro;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\models\CryptoproKey;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\modules\certManager\components\ssl\X509FileModel;
use Exception;
use Yii;

class RosbankSignatureService
{
    /**
     * @param Document $document
     * @param ISO20022Type|Auth026Type $typeModel
     * @throws Exception
     */
    public function signDocument(Document $document, $typeModel): void
    {
        if (!property_exists($typeModel, 'rosbankEnvelope')) {
            throw new Exception("Document {$document->type} does not support Rosbank envelope");
        }

        $keys = $this->getKeys($document->sender);
        $documentBody = (string) $typeModel;
        $envelope = new RosbankEnvelope([
            'documentType' => $document->type,
            'documentBody' => $documentBody,
            'cryptoSystem' => $this->getCryptoSystem($keys),
            'clientCode'   => $this->getClientCode($document),
        ]);

        foreach ($keys as $i => $key) {
            $x509 = X509FileModel::loadData($key->certData);
            $envelope->signatures[] = [
                'signature'                  => $this->signDocumentWithKey($documentBody, $key),
                'signatureKind'              => $i + 1,
                'signatureCertificateSerial' => $key->serialNumber,
                'signatureCertificateIssuer' => null,
                'commonName'                 => $x509->getSubjectCN(),
                'signingTime'                => date('Y-m-d H:i:s'),
            ];
        }

        $typeModel->rosbankEnvelope = $envelope;
    }

    private function getKeys(string $senderTerminalAddress): array
    {
        $keys = CryptoproKey::findByTerminalId(Terminal::getIdByAddress($senderTerminalAddress));
        if (empty($keys)) {
            Yii::info("No CryptoPro keys found for terminal {$senderTerminalAddress}");
        }
        return $keys;
    }

    /**
     * @param CryptoproKey[] $keys
     * @return string
     */
    private function getCryptoSystem(array $keys): string
    {
        $cryptoSystem = null;
        foreach ($keys as $key) {
            $currentKeyCryptoSystem = null;

            $collection = CryptoProHelper::getCertInfo(null, [DriverCryptoPro::SHA1_HASH => $key->keyId]);
            $container = $collection->first();
            if (!$container) {
                throw new Exception("Cannot get certificate info for key {$key->keyId}");
            }

            $algo = $container[DriverCryptoPro::SIGNATURE_ALGORITHM];
            if (strpos($algo, '34.10-2012') !== false) {
                $currentKeyCryptoSystem = 'h';
            } else if (strpos($algo, '34.10-2001') !== false) {
                $currentKeyCryptoSystem = 'g';
            } else {
                throw new Exception("Key {$key->keyId} has unsupported algorithm: $algo");
            }

            if ($cryptoSystem === null) {
                $cryptoSystem = $currentKeyCryptoSystem;
            } elseif ($cryptoSystem !== $currentKeyCryptoSystem) {
                throw new Exception('Keys must have same algorithm');
            }
        }

        if ($cryptoSystem !== null) {
            return $cryptoSystem;
        }

        throw new Exception('Cannot determine crypto system');
    }

    private function getClientCode(Document $document): string
    {
        $terminalRemoteId = TerminalRemoteId::find()
            ->where([
                'terminalReceiver' => $document->receiver,
                'terminalId'       => $document->terminalId,
            ])
            ->one();

        $clientCode = $terminalRemoteId !== null && $terminalRemoteId->remoteId !== null
            ? $terminalRemoteId->remoteId
            : null;

        if ($clientCode !== null) {
            return $clientCode;
        }

        throw new Exception("Cannot find remote client code for document {$document->id}");
    }

    private function signDocumentWithKey(string $documentBody, CryptoproKey $key): string
    {
        $passwordKey = getenv('COOKIE_VALIDATION_KEY');
        $password = Yii::$app->security->decryptByKey(base64_decode($key->password), $passwordKey);

        $signature = RosbankSignHelper::sign($documentBody, $key->keyId, $password);

        if ($signature === false) {
            throw new Exception("Failed to sign document with key {$key->keyId}");
        }

        return preg_replace('/[\r\n]+/', '', $signature);
    }
}
