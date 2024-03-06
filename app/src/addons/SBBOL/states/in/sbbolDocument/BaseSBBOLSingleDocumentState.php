<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\BaseState;
use common\components\Resque;
use common\jobs\StateJob;
use Yii;

abstract class BaseSBBOLSingleDocumentState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var mixed */
    public $sbbolDocument;

    public static function enqueueProcessingJobs($documents, $requestId)
    {
        $jobClass = get_called_class();
        foreach ($documents as $document) {
            $jobId = Yii::$app->resque->enqueue(
                StateJob::class,
                [
                    'stateClass' => $jobClass,
                    'params' => serialize([
                        'requestId'     => $requestId,
                        'sbbolDocument' => $document
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
