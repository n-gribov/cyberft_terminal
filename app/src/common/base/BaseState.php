<?php


namespace common\base;


abstract class BaseState
{
    public function run()
    {
        return false;
    }

    public function decideState()
    {
        return null;
    }

    public function transfer($oldState)
    {
        throw new \Exception('Not implemented');
    }
}
