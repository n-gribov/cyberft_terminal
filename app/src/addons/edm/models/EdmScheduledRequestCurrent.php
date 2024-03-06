<?php

namespace addons\edm\models;

use Yii;

/**
 * This is the model class for table "edm_scheduledRequestCurrent".
 *
 * @property int $accountNumber
 * @property string $startTime
 * @property string $endTime
 * @property string $lastTime
 * @property string $currentDay
 * @property int $interval
 * @property string $weekDays
 */
class EdmScheduledRequestCurrent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	
    public $currentDaysSelect;
	
    public static function tableName()
    {
        return 'edm_scheduledRequestCurrent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accountNumber'], 'required'],
            [['accountNumber', 'interval'], 'integer'],
            [['startTime', 'endTime', 'lastTime', 'currentDay'], 'safe'],
            [['weekDays'], 'string', 'max' => 255],
            [['accountNumber'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'accountNumber' => 'Account Number',
            'startTime' => Yii::t('app', 'Time of the first request'),
            'endTime' => Yii::t('app', 'Time of the last request'),
            'lastTime' => 'Last Time',
            'currentDay' => 'Current Day',
            'interval' => Yii::t('app', 'Repeat every (min)'),
            'currentDaysSelect' => Yii::t('app', 'Days of week'),
        ];
    }
    
    public function validate($attributeNames = null, $clearErrors = true)
    {
	if ($this->interval != 0 && $this->interval < 10) {
	    $this->addError('interval', Yii::t('app', 'The minimal non-zero value is 10 min'));

            return false;
	}
	
	return true;
    }
}
