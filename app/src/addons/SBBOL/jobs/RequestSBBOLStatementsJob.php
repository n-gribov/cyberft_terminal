<?php

namespace addons\SBBOL\jobs;

use addons\SBBOL\helpers\SBBOLModuleHelper;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\settings\SBBOLSettings;
use common\models\Time;
use Exception;
use Resque_Job_DontPerform;
use yii\helpers\ArrayHelper;

class RequestSBBOLStatementsJob extends BaseJob
{
    /** @var \DateTime */
    private $date;

    public function setUp()
    {
        parent::setUp();

        if (!$this->shouldRun()) {
            throw new Resque_Job_DontPerform('Do not need to run');
        }

        if (!$this->shouldRunNow()) {
            throw new Resque_Job_DontPerform('Do not need to run now');
        }

        $dateString = $this->args['date'] ?? 'yesterday';
        $date = new \DateTime($dateString);
        if (!$date) {
            throw new Resque_Job_DontPerform("Got invalid date argument: $dateString");
        }

        $this->date = $date;
    }

    public function perform()
    {
        $allAccounts = SBBOLCustomerAccount::find()
            ->joinWith('customer as customer')
            ->joinWith('customer.organization as organization')
            ->where('organization.terminalAddress is not null')
            ->andWhere("organization.terminalAddress != ''")
            ->all();

        $accountsByHolding = static::partitionAccountsByHolding($allAccounts);
        foreach ($accountsByHolding as $holdingHeadId => $accounts) {
            try {
                $this->requestStatementForAccounts($accounts);
            } catch (Exception $exception) {
                $this->log("Failed to send statements request, caused by: $exception", true);
            }
        }
    }

    private function requestStatementForAccounts(array $accounts)
    {
        $accountsNumbers = ArrayHelper::getColumn($accounts, 'number');
        $accountsNumbersString = implode(', ', $accountsNumbers);
        $this->log("Will send statements request for accounts $accountsNumbersString...", true);

        list($isSent, $errorMessage) = SBBOLModuleHelper::sendStatementRequestToSBBOL(
            $accounts,
            $this->date,
            $this->date
        );

        if (!$isSent) {
            throw new \Exception($errorMessage);
        }
    }

    private function shouldRunNow(): bool
    {
        $now = new Time((new \DateTime())->format('H:i'));
        $timeFrom = $this->getSettingsTimeValue($this->args['timeFromSettingsKey']);
        $timeTo = $this->getSettingsTimeValue($this->args['timeToSettingsKey']);
        return $now->greaterThanOrEqualTo($timeFrom) && $now->lessThanOrEqualTo($timeTo);
    }

    private function shouldRun(): bool
    {
        $settingsCheckKey = $this->args['settingsCheckKey'] ?? null;

        if ($settingsCheckKey === null) {
            return true;
        }

        return (bool)$this->getSettingsValue($settingsCheckKey);
    }

    private function getSettingsValue(string $key)
    {
        /** @var SBBOLSettings $settings */
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

    /**
     * @param SBBOLCustomerAccount[] $accounts
     * @return array
     */
    private static function partitionAccountsByHolding(array $accounts)
    {
        return array_reduce(
            $accounts,
            function ($carry, SBBOLCustomerAccount $account) {
                $key = $account->customer->holdingHeadId;
                $carry[$key][] = $account;
                return $carry;
            },
            []
        );
    }
}
