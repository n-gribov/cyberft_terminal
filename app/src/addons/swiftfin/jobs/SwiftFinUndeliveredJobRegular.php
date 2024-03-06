<?php

namespace addons\swiftfin\jobs;

use addons\swiftfin\helpers\SwiftfinHelper;
use common\base\RegularJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use Resque_Job_DontPerform;
use Yii;

class SwiftFinUndeliveredJobRegular extends RegularJob
{
	private $_module;

	public function setUp()
	{
		parent::setUp();

		$this->_module = Yii::$app->getModule('swiftfin');

        if (empty($this->_module)) {
            throw new Resque_Job_DontPerform('SwiftFin module not found');
        }
	}

	public function perform()
	{
        $ttl = (int) $this->_module->settings->deliveryExportTTL;
        if (!$this->_module->settings->deliveryExport || $ttl == 0) {
            return;
        }

		$now = date_create();
		$nowTimestamp = date_timestamp_get($now) - $ttl * 60;

  		$documentList = Document::find()->where([
            'typeGroup' => $this->_module->getServiceId(),
            'direction' => Document::DIRECTION_OUT,
            'status' => [Document::STATUS_DELIVERING, Document::STATUS_SENT]
        ])
        ->andWhere(['<', 'dateCreate', date('Y-m-d H:i:s', $nowTimestamp)])
        ->limit(20)->all();

		foreach($documentList as $document) {
            DocumentHelper::updateDocumentStatus($document, Document::STATUS_UNDELIVERED);
			SwiftfinHelper::exportUndeliveredReport($document);
		}
	}
}