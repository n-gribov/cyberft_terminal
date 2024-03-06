<?php

namespace common\components\cryptography;

interface CryptographyInterface
{
	public function sign($data, $privateKey, $password = NULL);
	public function verify($data, $signature, $certificate, $signature_alg = 'sha1');
}