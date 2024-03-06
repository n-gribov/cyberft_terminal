<?php

namespace addons\edm\models\SBBOLStmtReq;

use addons\edm\models\BaseSBBOLDocument\BaseSBBOLRequestDocumentCyberXmlContent;

class SBBOLStmtReqCyberXmlContent extends BaseSBBOLRequestDocumentCyberXmlContent
{
    const TYPE_MODEL_CLASS = SBBOLStmtReqType::class;
    const ROOT_ELEMENT = 'SBBOLStmtReq';
}
