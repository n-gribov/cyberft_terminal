<?php

namespace addons\raiffeisen\states;

use addons\raiffeisen\RaiffeisenModule;
use Yii;

abstract class BaseState extends \common\base\BaseState
{
    /** @var string */
    public $name;

    /** @var RaiffeisenModule */
    public $module;

    /** @var BaseStep[] */
    protected $steps = [];

    public function __construct($params = [])
    {
        $class = get_called_class();
        foreach ($params as $key => $value) {
            if (property_exists($class, $key)) {
                $this->$key = $value;
            }
        }

        $this->module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);
        $this->name = (new \ReflectionClass($this))->getShortName();
    }

    public function run()
    {
        $startTime = microtime(true);
        $runSteps = [];

        $isSuccess = true;
        foreach ($this->nextStep() as $stepName => $step) {
            $runSteps[] = $step->name;

            try {
                $isSuccess = $step->run();
            } catch (\Exception $exception) {
                $this->log("{$step->name} has thrown exception: $exception");
                $isSuccess = false;
            }

            if (!$isSuccess) {
                break;
            }
        }

        $runTime = microtime(true) - $startTime;
        $this->logRunStats($runSteps, $runTime);

        return $isSuccess;
    }

    public function nextStep()
    {
        foreach ($this->steps as $stepName => $stepClass) {
            if ($stepClass === null) {
                $stepClass = $this->decideStep() ?: DoNothingStep::class;
            }
            yield $stepName => new $stepClass($this);
        }
    }

    public function log($message)
    {
        Yii::info("{$this->name}: $message");
    }

    private function logRunStats($runSteps, $runTime)
    {
        $message = sprintf(
            "%s %0.2f",
            implode('|', $runSteps),
            $runTime
        );
        $this->log($message);
    }

    protected function decideStep()
    {
        return null;
    }

}
