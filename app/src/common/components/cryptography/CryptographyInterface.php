<?php

namespace common\components\cryptography;

interface CryptographyInterface
{
	public function sign($data, $privateKey, $password = null);
	public function verify($data, $signature, $certificate, $signature_alg = 'sha1');
}