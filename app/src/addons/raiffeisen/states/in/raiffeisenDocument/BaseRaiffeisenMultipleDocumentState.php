<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument;

use addons\raiffeisen\states\BaseState;
use common\components\Resque;
use common\jobs\StateJob;
use Yii;

abstract class BaseRaiffeisenMultipleDocumentState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var array */
    public $raiffeisenDocuments;

    public static function enqueueProcessingJobs($documents, $requestId)
    {
        $jobClass = get_called_class();
        $jobId = Yii::$app->resque->enqueue(
            StateJob::class,
            [
                'stateClass' => $jobClass,
                'params' => serialize([
                    'requestId' => $requestId,
                    'raiffeisenDocuments' => $documents
                ])
            ],
            true,
            Resque::INCOMING_QUEUE
        );

        if ($jobId === false) {
            Yii::warning("Failed to enqueue $jobClass job");
        }
    }
}
