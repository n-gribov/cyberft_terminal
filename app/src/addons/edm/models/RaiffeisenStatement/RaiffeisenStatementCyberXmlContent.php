<?php

namespace addons\edm\models\RaiffeisenStatement;

use addons\edm\models\BaseRaiffeisenDocument\BaseRaiffeisenResponseDocumentCyberXmlContent;

class RaiffeisenStatementCyberXmlContent extends BaseRaiffeisenResponseDocumentCyberXmlContent
{
    const TYPE_MODEL_CLASS = RaiffeisenStatementType::class;
    const ROOT_ELEMENT = 'RaiffeisenStatement';
}
