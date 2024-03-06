<?php

namespace common\modules\monitor\jobs;

use common\base\Job;
use Yii;
use common\helpers\CryptoProHelper;

class CryptoProCertChecker extends Job
{
    public function perform()
    {
        $cpLicense = CryptoProHelper::checkCPLicense();

        if (!$cpLicense) {
            Yii::$app->monitoring->log('user:CryptoProCertExpired', '', '');
        }

        return true;
    }
}