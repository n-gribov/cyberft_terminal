<?php

namespace addons\edm\models\Sbbol2PayDocRu;

use addons\edm\models\BaseSbbol2Document\BaseSbbol2DocumentCyberXmlContent;

class Sbbol2PayDocRuCyberXmlContent extends BaseSbbol2DocumentCyberXmlContent
{
    const TYPE_MODEL_CLASS = Sbbol2PayDocRuType::class;
    const ROOT_ELEMENT = 'Sbbol2PayDocRu';
}
