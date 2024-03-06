<?php

namespace addons\edm\models\VTBDocument\presenters\config;

class ConfDocInquiry138IPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'DOCATTACHMENT' => 'Вложения',
        'CONFDOCPSBLOB' => 'Информация о подтверждающих документах для паспортизируемых сделок',
        'CONFDOCNOTPSBLOB' => 'Информация о подтверждающих документах для не паспортизируемых сделок'
    ];

    const EXCLUDED_FIELDS = [
        'CONTRACTNUMBER',
        'CONTRACTDATE',
        'ISCONTRACTNUMBER',
        'ADDINFO',
        'FCORRECTION',
        'CORRECTIONNUM',
    ];
}
