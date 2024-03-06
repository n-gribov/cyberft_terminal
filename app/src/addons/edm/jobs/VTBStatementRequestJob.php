<?php

namespace addons\edm\jobs;

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmScheduledRequestCurrent;
use addons\edm\models\EdmScheduledRequestPrevious;
use addons\edm\models\StatementRequest\StatementRequestType;
use common\base\Job;
use Exception;
use Resque_Job_DontPerform;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Export EDM document to 1C or Excel format
 */
class VTBStatementRequestJob extends Job
{
    private $_module;

    private $_settingsModel;
    private $_date;

    public function setUp()
    {
        parent::setUp();

        $this->_module = Yii::$app->addon->getModule('edm');

        if (empty($this->_module)) {
            $this->log('EDM module not found');
            throw new Resque_Job_DontPerform('EDM module not found');
        }		

        $dateString = $this->args['date'] ?? 'yesterday';

        if ( $dateString === 'yesterday' ) {
            $this->_settingsModel = EdmScheduledRequestPrevious::find()->all();
        } else if ( $dateString === 'today' ) {
            $this->_settingsModel = EdmScheduledRequestCurrent::find()->all();
        } else {
            throw new Resque_Job_DontPerform("Requests must be for yesterday or today");	
        }

        $this->_date = date('Y-m-d', strtotime($dateString)); 
    }

    public function perform()
    {
        $this->searchInSettings($this->_settingsModel);
    }	
	
    private function searchInSettings($settings)
    {
        foreach ($settings as $record) {
            if ( $record->currentDay != date('Y-m-d') ) {
                $record->currentDay = date('Y-m-d');
                $record->lastTime = null;
                // Сохранить модель в БД
                $record->save();
            }
			
            $weekDay = date('N');

            if ( strpos($record->weekDays, $weekDay) !== false ) {
                if ( !isset($record->lastTime) ) {
                    if ( $record->interval == 0 ) {
                        if ( date('H:i:s') >= date('H:i:s', strtotime($record->startTime)) ) {
                            $record->lastTime = date('H:i:s');
                            // Сохранить модель в БД
                            $record->save();
                            $this->requestStatement($record->accountNumber);
                        }
                    } else {
                        if ( date('H:i:s') >= date('H:i:s', strtotime($record->startTime)) &&
                             date('H:i:s') <= date('H:i:s', strtotime($record->endTime))
                        ) {
                            $record->lastTime = date('H:i:s');
                            // Сохранить модель в БД
                            $record->save();
                            $this->requestStatement($record->accountNumber);
                        }
                    }
                } else {
                    /*
                     * При интервале 0 делаем запрос один раз за день
                     * Записанный lastTime означает, что запрос уже делали или сегодня новых запросов не будет
                     */
                    if ( $record->interval != 0 ) {
                        if ( strtotime(date('H:i:s')) >= strtotime($record->lastTime) + $record->interval*60 &&
                             strtotime(date('H:i:s')) <= strtotime($record->endTime)
                        ) {
                            $record->lastTime = date('H:i:s');
                            // Сохранить модель в БД
                            $record->save();
                            $this->requestStatement($record->accountNumber);
                        }
                    }
                }
            }
        }		
    }
	
    private function requestStatement($accountNumber)
    {
        $model = new StatementRequestType();
        $model->accountNumber = $accountNumber;
        $model->startDate = $this->_date;
        $model->endDate = $this->_date;

        try {
            $account = EdmPayerAccount::findOne(['number' => $model->accountNumber]);
            $model->BIK = $account->bankBik;

            if (!$model->validate()) {
                throw new BadRequestHttpException($model->getErrorsSummary(true));
            }
			
            $this->log("Will send statement request for account $accountNumber...", true);
            $organization = DictOrganization::findOne($account->organizationId);

            $document = EdmHelper::createStatementRequest($model, $organization->terminal);
        } catch (Exception $ex) {
            $this->log("Failed to send statement request, caused by: $ex", true);
        }
    }
}
