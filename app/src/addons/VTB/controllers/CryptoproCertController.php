<?php

namespace addons\VTB\controllers;

use backend\controllers\helpers\CryptoproCertsActions;
use common\models\CryptoproCert;
use yii\web\Controller;

class CryptoproCertController extends Controller
{
    use CryptoproCertsActions;

    private $certModel;
    private $journalLink;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->certModel = CryptoproCert::getInstance('VTB');
        $this->journalLink = '/VTB/settings?tabMode=tabCryptoPro&subTabMode=subTabCryptoProCerts';
    }

}
