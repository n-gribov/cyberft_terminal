<?php

namespace addons\raiffeisen\helpers;

use Yii;

class RaiffeisenSignHelper
{
    public static function sign($data, $certId, $keyPassword)
    {
        $dataFileName = tempnam('/tmp', '');
        $signatureFileName = tempnam('/tmp', '');

        file_put_contents($dataFileName, $data);

        $command = '/opt/cprocsp/bin/amd64/csptestf'
            . ' -sfsign'
            . ' -sign'
            . ' -detached'
            . ' -base64'
            . ' -in ' . escapeshellarg($dataFileName)
            . ' -out ' . escapeshellarg($signatureFileName)
            . ' -my ' . escapeshellarg($certId)
            . ' -password ' . escapeshellarg($keyPassword)
            . ' -silent';

        $isSigned = static::executeCryptoproCommand($command);
        $result = $isSigned ? file_get_contents($signatureFileName) : false;

        unlink($dataFileName);
        unlink($signatureFileName);

        return $result;
    }

    public static function verify($data, $signature, $certId)
    {
        $dataFileName = tempnam('/tmp', '');
        $signatureFileName = tempnam('/tmp', '');

        file_put_contents($dataFileName, $data);
        file_put_contents($signatureFileName, $signature);

        $command = '/opt/cprocsp/bin/amd64/csptestf'
            . ' -sfsign'
            . ' -verify'
            . ' -detached'
            . ' -base64'
            . ' -in ' . escapeshellarg($dataFileName)
            . ' -signature ' . escapeshellarg($signatureFileName)
            . ' -my ' . escapeshellarg($certId);

        $isValid = static::executeCryptoproCommand($command);

        unlink($dataFileName);
        unlink($signatureFileName);

        return $isValid;
    }

    private static function executeCryptoproCommand($command)
    {
        exec($command, $outputLines, $resultCode);
        $statusString = !empty($outputLines) ? $outputLines[count($outputLines) - 1] : null;

        if ($resultCode === 0 && $statusString === '[ErrorCode: 0x00000000]') {
            return true;
        }

        $output = implode(', ', $outputLines);
        Yii::info("Cryptcp failed, result: $resultCode, output: $output");
        return false;
    }
}
