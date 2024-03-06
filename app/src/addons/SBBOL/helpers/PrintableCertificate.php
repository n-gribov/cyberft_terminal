<?php

namespace addons\SBBOL\helpers;

use addons\SBBOL\models\SBBOLCustomerKeyOwner;
use addons\SBBOL\models\SBBOLKey;
use common\modules\certManager\utils\DocxTemplate;
use Yii;

class PrintableCertificate
{
    public static function generateForKey(SBBOLKey $key)
    {
        return static::generateForKeyOwner(
            $key->keyOwner,
            $key->bicryptId,
            $key->certificateRequest,
            $key->publicKey
        );
    }

    public static function generateForKeyOwner(SBBOLCustomerKeyOwner $keyOwner, $bicryptId, $certificateRequestBody, $publicKeyBody)
    {
        $certificateRequestSha1 = sha1(static::pemToBin($certificateRequestBody));
        $tempDirPath = Yii::getAlias('@temp/sbol-printable-certificates');
        $docxTemplate = new DocxTemplate($tempDirPath);
        $templatePath = Yii::getAlias('@addons/SBBOL/resources/printable-certificate-template.docx');

        $templateData = [
            'bicryptId'              => $bicryptId,
            'keyId'                  => substr($bicryptId, 0, 8),
            'keyOwnerName'           => $keyOwner->fullName,
            'keyOwnerPosition'       => $keyOwner->position,
            'customerName'           => $keyOwner->customer->shortName,
            'certificateRequestSha1' => static::formatHex($certificateRequestSha1),
            'cryptoSoftwareName'     => 'КриптоПро',
            'cryptoSoftwareVendor'   => 'ООО "КРИПТО-ПРО"',
        ];

        $formattedPublicKey = static::formatHex($publicKeyBody, 16 * 16, 16);
        foreach (preg_split('/[\r\n]+/', $formattedPublicKey) as $i => $line) {
            $lineNumber = 1 + $i;
            $templateData["publicKey$lineNumber"] = $line;
        }

        return $docxTemplate->render($templatePath, $templateData);
    }

    private static function formatHex($hexValue, $padBytesLength = null, $bytesInRow = null) {
        $hexValue = preg_replace('/[^\dA-Za-z]/', '', $hexValue);
        $paddedHexValue = $padBytesLength !== null
            ? str_pad($hexValue, $padBytesLength * 2, '0')
            : $hexValue;

        $bytes = str_split($paddedHexValue, 2);

        $bytesRows = $bytesInRow !== null
            ? array_chunk($bytes, $bytesInRow)
            : [$bytes];

        $rows = array_map(
            function ($rowBytes) {
                return implode(' ', $rowBytes);
            },
            $bytesRows
        );

        return strtoupper(implode(PHP_EOL, $rows));
    }

    private static function pemToBin($pemContent)
    {
        $pemBody = preg_replace('/\-\-\-.*?\-\-\-[\r\n]+/', '', $pemContent);

        return base64_decode($pemBody);
    }
}
