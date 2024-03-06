<?php
namespace common\helpers;

//use yii\base\Component;

/**
 * Condition parser
 * @author a.nikolaenko
 */
class ConditionParser
{
	public static function checkConditions($conditions, $model, $mode = 'or')
	{
		$result = $mode == 'and';

		foreach($conditions as $cond) {
			$currentResult = static::processCondition($cond, $model, $mode);
			if ($mode == 'and') {
				if ($currentResult == false) {
					return false;
				}
			} else {
				$result |= $currentResult;
			}
		}

		return $result;
	}

	private static function processCondition($cond, $model, $mode)
	{
		if (!array_key_exists('attr', $cond)) {
			// this is recursive block
			$mode = $mode == 'and' ? 'or' : 'and';
			return static::checkConditions($cond, $model, $mode);
		}

		$attr = $cond['attr'];
		$op = $cond['op'];
		$value = $cond['value'];

		$attrValue = $model->$attr;

		switch($op) {
			case '=': return $attrValue == $value;
			case '!=': return $attrValue != $value;
			case '<': return $attrValue < $value;
			case '>': return $attrValue > $value;
			case 'has': return in_array($value, $attrValue);
			default: return false;
		}
	}
}