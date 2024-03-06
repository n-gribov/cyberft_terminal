<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument;

use addons\raiffeisen\states\BaseState;
use common\components\Resque;
use common\jobs\StateJob;
use Yii;

abstract class BaseRaiffeisenSingleDocumentState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var mixed */
    public $raiffeisenDocument;

    public static function enqueueProcessingJobs($documents, $requestId)
    {
        $jobClass = get_called_class();
        foreach ($documents as $document) {
            $jobId = Yii::$app->resque->enqueue(
                StateJob::class,
                [
                    'stateClass' => $jobClass,
                    'params' => serialize([
                        'requestId' => $requestId,
                        'raiffeisenDocument' => $document
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
}
