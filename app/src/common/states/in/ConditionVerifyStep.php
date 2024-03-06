<?php

namespace common\states\in;

use common\document\Document;
use common\helpers\ConditionParser;
use common\models\CompoundCondition;
use common\models\cyberxml\CyberXmlDocument;
use common\states\BaseDocumentStep;
use Exception;

class ConditionVerifyStep extends BaseDocumentStep
{
    const INITIAL_INCOMING_STATE = Document::STATUS_VERIFIED;
	const PROCESSING_STATE = Document::STATUS_VERIFICATION;
	const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_VERIFIED;
	const ERRONEOUS_PROCESSED_STATE = Document::STATUS_VERIFICATION_FAILED;

    public $name = 'condVerify';

    public function run()
    {
        if ($this->state->document->status === static::INITIAL_INCOMING_STATE) {
            /**
             * Рассматриваем только документы, прошедшие верификацию подписи
             */

            $result = false;

            try {
                $result = $this->verifyConditions($this->state->cyxDoc);
            } catch(Exception $ex) {
                $this->log('Exception: ' . $ex->getMessage());
            }

            if (!$result) {
                $this->state->document->updateStatus(static::ERRONEOUS_PROCESSED_STATE, 'Conditions do not match');
                $this->log('Failed condition verification');
            }
        }

        /**
         * Внимание: этот шаг всегда возвращает true,
         * cледующим шагом должен быть StatusReportStep, который вернет false или true в зависимости
         * от полученного здесь результата.
         */

        return true;
    }

    private function verifyConditions(CyberXmlDocument $cyx)
	{
		if (empty($this->state->module)) {
			return true;
		}

		$serviceId = $this->state->module->getServiceId();
		$sender = $this->state->document->sender;

		$searchPath = implode('/', [$serviceId, $sender]);
		$condList = CompoundCondition::find()
				->andWhere(['serviceId' => $serviceId])
				->andWhere(['type' => 'incomingVerification'])
				->andWhere(['like', 'searchPath', $searchPath])
				->all();

		if (!count($condList)) {
			return true;
		}

		$sum = $cyx->sum;
		$result = true;

		foreach($condList as $cond) {
			$path = explode('/', $cond->searchPath);
			$currency = $path[2];
			$fromSum = $path[3];
			$toSum = $path[4];

			if (
				$currency != $cyx->currency
				|| $sum <= $fromSum
				|| ($sum >= $toSum && $toSum > 0)
			) {
				// doc is not suitable for checking
				continue;
			}

			$result = $result && ConditionParser::checkConditions($cond->conditions, $this->state->document);
		}

		return $result;
	}

}
