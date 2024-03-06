<?php

namespace addons\raiffeisen\console;

use addons\raiffeisen\jobs\RequestRaiffeisenIncomingDocumentsJob;
use Yii;

class JobsController extends BaseController
{
    public function actionRequestIncoming()
    {
        Yii::$app->resque->enqueue(RequestRaiffeisenIncomingDocumentsJob::class);
    }
}
