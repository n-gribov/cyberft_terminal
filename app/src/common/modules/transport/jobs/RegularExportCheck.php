<?php
namespace common\modules\transport\jobs;

use common\base\RegularJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use Yii;

class RegularExportCheck extends RegularJob
{
    const MAX_ATTEMPTS_COUNT = 10;

	public function perform()
	{
        $this->log('Checking for documents failed to export...', false, 'regular-jobs');

        $documents = Document::find()
			->where(['status' => Document::STATUS_NOT_EXPORTED])
            ->andWhere(['<', 'attemptsCount', self::MAX_ATTEMPTS_COUNT])
			->all();

        foreach($documents as $doc) {

            $jobs = Yii::$app->registry->getTypeRegisteredAttribute($doc->type, $doc->typeGroup, 'jobs');

            if (empty($jobs) || !isset($jobs['export'])) {
                continue;
            }

            DocumentHelper::updateDocumentStatus(
                $doc,
                Document::STATUS_EXPORT_RETRY,
                $doc->attemptsCount + 1
            );

            $this->log($doc->type . ' ' . $doc->id . ' repeating job ' . $jobs['export'] . ', attempt ' . $doc->attemptsCount);
            Yii::$app->resque->enqueue($jobs['export'], ['documentId' => $doc->id]);
        }

	}

}