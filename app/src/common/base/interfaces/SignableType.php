<?php


namespace common\base\interfaces;


interface SignableType
{
    public function injectSignature($signatureValue, $certBody);

    public function getSignedInfo(?string $signerCertificate = null);
}
