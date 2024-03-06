<?php

namespace common\helpers;

use common\modules\certManager\models\Cert;

class TerminalAddressResolver
{
    private $knownAddresses;

    public function __construct(array $knownAddresses)
    {
        $this->knownAddresses = $knownAddresses;
    }

    /**
     * @param string $swiftCode
     * @return string|null
     */
    public function resolve($swiftCode)
    {
        $swiftCodeLength = strlen($swiftCode);
        switch ($swiftCodeLength) {
            case 12:
                return $this->resolveTwelveCharactersSwiftCode($swiftCode);
            case 11:
                return $this->resolveElevenCharactersSwiftCode($swiftCode);
            case 8:
                return $this->resolveEightCharactersSwiftCode($swiftCode);
            default:
                \Yii::info("Cannot resolve terminal address, unsupported swift code length: $swiftCode");
                return null;
        }
    }

    /**
     * @param string $receiverSwiftCode
     * @return string|null
     */
    public static function resolveReceiver($receiverSwiftCode)
    {
        $controllersCerts = Cert::find()
            ->where(['status' => Cert::STATUS_C10, 'role' => Cert::ROLE_SIGNER_BOT])
            ->all();

        $activeControllersCerts = array_filter(
            $controllersCerts,
            function (Cert $cert) {
                return !$cert->isExpired();
            }
        );

        $availableReceiversAddresses = array_map(
            function (Cert $cert) {
                return $cert->getTerminalAddress();
            },
            $activeControllersCerts
        );

        $resolver = new static($availableReceiversAddresses);
        $address = $resolver->resolve($receiverSwiftCode);
        if ($address === null) {
            \Yii::info("Failed to resolve receiver terminal address for swift code $receiverSwiftCode");
        }

        return $address;
    }

    private function resolveTwelveCharactersSwiftCode(string $swiftCode)
    {
        return $swiftCode;
    }

    private function resolveElevenCharactersSwiftCode(string $swiftCode)
    {
        $knownAddresses = $this->getSortedKnownAddresses();
        list($firstEight, $lastThree) = str_split($swiftCode, 8);
        foreach ($knownAddresses as $address) {
            $firstEightMatch = strpos($address, $firstEight) === 0;
            $lastThreeMatch = substr($address, -3) === $lastThree;
            if ($firstEightMatch && $lastThreeMatch) {
                return $address;
            }
        }

        return null;
    }

    private function resolveEightCharactersSwiftCode(string $swiftCode)
    {
        $knownAddresses = $this->getSortedKnownAddresses();
        $firstEight = substr($swiftCode, 0, 8);
        $matchingAddress = null;
        foreach ($knownAddresses as $address) {
            $firstEightMatch = strpos($address, $firstEight) === 0;
            if (!$firstEightMatch) {
                continue;
            }

            $endsWithXxx = substr($address, -3) === 'XXX';
            if ($endsWithXxx) {
                return $address;
            } elseif ($matchingAddress === null) {
                $matchingAddress = $address;
            }
        }

        return $matchingAddress;
    }

    private function getSortedKnownAddresses(): array
    {
        $knownAddresses = $this->knownAddresses;
        sort($knownAddresses);
        return $knownAddresses;
    }
}
