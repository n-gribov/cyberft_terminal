<?php

namespace addons\edm\models\ConfirmingDocumentInformation;

use common\models\listitem\BaseAttachedFileSession;

class AttachedFileSession extends BaseAttachedFileSession
{
    const SERVICE_ID = 'edm';
    const SESSION_KEY = 'CDIItemAttachedFiles';
}
