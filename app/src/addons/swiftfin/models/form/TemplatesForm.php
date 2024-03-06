<?php

namespace addons\swiftfin\models\form;

use Yii;
use common\base\Model;

class TemplatesForm extends Model {

    public $docType;
	public $title;
	public $text;
	public $comment;

	public function rules() {
		return [
            [['docType', 'title', 'text'], 'required'],
            [['comment'], 'string'],
		];
	}

    public function attributeLabels()
    {
        return [
            'docType' => Yii::t('document', 'Type'),
            'title' => Yii::t('document', 'Title'),
            'comment' => Yii::t('document', 'Comment'),
            'text' => Yii::t('document', 'Template text'),
        ];
    }
}