<?php

namespace addons\raiffeisen\console;

use addons\raiffeisen\helpers\CryptoProHelper;

class KeysController extends BaseController
{
    public function actionInstall(string $containerZipFilePath, string $certificatePath, string $containerPassword)
    {
        $containerName = CryptoProHelper::importContainerFromZipFile($containerZipFilePath);
        CryptoProHelper::installCertificateIntoContainer($certificatePath, $containerName, $containerPassword);
    }
}
