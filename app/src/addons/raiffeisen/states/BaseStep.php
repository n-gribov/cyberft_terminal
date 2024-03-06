<?php


namespace addons\raiffeisen\states;


abstract class BaseStep
{
    /** @var BaseState */
    protected $state;

    /** @var string */
    public $name;

    public function __construct(BaseState $state)
    {
        $this->state = $state;
        $this->name = $this->createName();
    }

    public abstract function run();

    protected function log($message)
    {
        $this->state->log("{$this->name}: $message");
    }

    protected function createName()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $name = preg_replace('/Step$/', '', $className);

        return lcfirst($name);
    }

}
