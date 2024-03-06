<?php


namespace addons\raiffeisen\states;


class DoNothingStep extends BaseStep
{
    public function run()
    {
        return true;
    }
}
