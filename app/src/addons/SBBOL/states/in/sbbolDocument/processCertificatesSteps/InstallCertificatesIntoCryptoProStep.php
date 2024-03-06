<?php

namespace addons\SBBOL\states\in\sbbolDocument\processCertificatesSteps;

use addons\SBBOL\helpers\CryptoProHelper;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessCertificatesState;
use common\modules\certManager\components\ssl\X509FileModel;

/**
 * @property ProcessCertificatesState $state
 */
class InstallCertificatesIntoCryptoProStep extends BaseStep
{
    public function run()
    {
        foreach ($this->state->sbbolDocuments as $certificate) {
            $certificateBody = base64_decode($certificate->value());

            if (!X509FileModel::isCertificate($certificateBody)) {
                $this->log("Not a X.509 certificate, will not add it to CryptoPro: $certificateBody");
                continue;
            }

            $certificateX509 = X509FileModel::loadData($certificateBody);
            $fingerprint = $certificateX509->getFingerprint();
            $this->installCertificate($certificateBody, $fingerprint);
        }

        return true;
    }

    private function installCertificate(string $certificateBody, string $fingerprint)
    {
        $this->log("Installing certificate $fingerprint...");

        $fileName = tempnam('/tmp', 'sbbol-cert');
        try {
            file_put_contents($fileName, $certificateBody);
            CryptoProHelper::installCertificate($fileName);
        } catch (\Exception $exception) {
            $this->log("Installing certificate $fingerprint has failed, caused by: $exception");
        } finally {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }
}
