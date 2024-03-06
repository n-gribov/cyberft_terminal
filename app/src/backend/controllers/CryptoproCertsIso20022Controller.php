<?php

namespace backend\controllers;

use backend\controllers\helpers\CryptoproCertsActions;
use common\models\CryptoproCert;
use yii\web\Controller;

class CryptoproCertsIso20022Controller extends Controller
{
    use CryptoproCertsActions;

    private $certModel;
    private $journalLink;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->certModel = CryptoproCert::getInstance('ISO20022');
        $this->journalLink = '/ISO20022/settings?tabMode=tabCryptoPro&subTabMode=subTabCryptoProCerts';
    }

}