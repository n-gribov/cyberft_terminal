<?php

namespace addons\raiffeisen\jobs;

use addons\raiffeisen\helpers\RaiffeisenModuleHelper;
use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\settings\RaiffeisenSettings;
use common\models\Time;
use Resque_Job_DontPerform;

class RequestRaiffeisenIncomingDocumentsJob extends BaseJob
{
    public function setUp()
    {
        parent::setUp();

        if (!$this->shouldRunNow()) {
            throw new Resque_Job_DontPerform('Do not need to run now');
        }
    }

    public function perform()
    {
        /** @var RaiffeisenCustomer $holdingHeadCustomers */
        $holdingHeadCustomers = RaiffeisenCustomer::find()
            ->where(['isHoldingHead' => true])
            ->all();

        foreach ($holdingHeadCustomers as $holdingHeadCustomer) {
            try {
                RaiffeisenModuleHelper::sendIncomingRequest(
                    $holdingHeadCustomer,
                    function ($message) { $this->log($message); }
                );
            } catch (\Exception $exception) {
                $this->log("Failed to send incoming documents request, caused by: $exception", true);
            }
        }
    }

    private function shouldRunNow(): bool
    {
        $now = new Time((new \DateTime())->format('H:i'));
        $timeFrom = $this->getSettingsTimeValue($this->args['timeFromSettingsKey']);
        $timeTo = $this->getSettingsTimeValue($this->args['timeToSettingsKey']);
        return $now->greaterThanOrEqualTo($timeFrom) && $now->lessThanOrEqualTo($timeTo);
    }

    private function getSettingsValue(string $key)
    {
        /** @var RaiffeisenSettings $settings */
        $settings = $this->module->settings;
        if (!property_exists($settings, $key)) {
            $this->log("Module settings has no '$key' property", true);
            return null;
        }

        return $settings->$key;
    }

    private function getSettingsTimeValue(string $key): Time
    {
        $value = $this->getSettingsValue($key);
        return new Time($value);
    }
}
