<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\BaseState;
use common\components\Resque;
use common\jobs\StateJob;
use Yii;

abstract class BaseSBBOLMultipleDocumentState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var array */
    public $sbbolDocuments;

    public static function enqueueProcessingJobs($documents, $requestId)
    {
        $jobClass = get_called_class();
        $jobId = Yii::$app->resque->enqueue(
            StateJob::class,
            [
                'stateClass' => $jobClass,
                'params' => serialize([
                    'requestId'      => $requestId,
                    'sbbolDocuments' => $documents
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
