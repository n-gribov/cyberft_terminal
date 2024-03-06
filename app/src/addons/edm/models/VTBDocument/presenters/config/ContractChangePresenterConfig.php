<?php

namespace addons\edm\models\VTBDocument\presenters\config;

class ContractChangePresenterConfig extends BSDocumentPresenterConfig
{
    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'CUSTOMERNAME',
            'CUSTOMERINN',
            'CUSTOMERBANKNAME',
            'SENDEROFFICIALS',
            'PHONEOFFICIALS',
            'DOCATTACHMENT'
        ];
    }

}
