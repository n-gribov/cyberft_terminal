<?php

namespace backend\controllers;

use backend\controllers\helpers\CryptoproCertsActions;
use common\models\CryptoproCert;
use yii\web\Controller;

class CryptoproCertsFileactController extends Controller
{
    use CryptoproCertsActions;

    private $certModel;
    private $journalLink;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->certModel = CryptoproCert::getInstance('fileact');
        $this->journalLink = '/fileact/settings?subTabMode=subTabCryptoProCerts';
    }

}