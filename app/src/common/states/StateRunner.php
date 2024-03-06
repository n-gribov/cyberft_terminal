<?php
namespace common\states;

class StateRunner
{
    public static function run($state)
    {
        $result = false;

        try {
            while(!empty($state)) {
                $result = $state->run();
                if (!$result) {
                    /**
                     * unsuccesful state run prevents continue to other states
                     */
                    break;
                }

                /**
                 * Результатом выполнения State может быть новый State
                 */
                $newState = $state->decideState();

                if (empty($newState)) {
                    break;
                }

                //$this->log('Transferring state to ' . get_class($newState));
                $newState->transfer($state);
                $state = $newState;
            }
        } catch (Exception $ex) {
            \Yii::info($ex->getMessage());
            $result = false;
        }

        return $result;
    }

}