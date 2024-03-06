<?php

namespace common\helpers;

use yii\base\Model;

class ModelHelper
{
    public static function getErrorsSummary(Model $model, bool $ignoreLabel = false): string
    {
        $summary = '';
        foreach ($model->errors as $field => $errors) {
            if ($summary) {
                $summary .= ' ';
            }
            $summary .= (!$ignoreLabel ? $model->getAttributeLabel($field) . ": " . (count($errors) > 1 ? "\n" : null) : null);
            $summary .= implode("\n", $errors);
        }

        return $summary;
    }
}
