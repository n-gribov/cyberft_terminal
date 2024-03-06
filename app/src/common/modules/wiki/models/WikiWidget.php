<?php

namespace common\modules\wiki\models;

use yii\db\ActiveRecord;

/**
 * Модель для таблицы,
 * связывающей виджет и статью в Wiki
 * Class Widget
 * @package common\modules\wiki\models
 */

class WikiWidget extends ActiveRecord
{
    public static function tableName()
    {
        return 'wiki_widgets';
    }

    public function rules()
    {
        return [
            ['pageId', 'integer'],
            ['pageId', 'safe'],
            ['widgetId', 'required'],
            ['widgetId', 'unique']
        ];
    }
}