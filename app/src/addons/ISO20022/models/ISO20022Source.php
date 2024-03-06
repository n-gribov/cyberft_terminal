<?php

namespace addons\ISO20022\models;

use Yii;
use yii\db\ActiveRecord;

class ISO20022Source extends ActiveRecord 
{
	const STATUS_ORIGINAL = 'original';
	const STATUS_SIGNED = 'signed';

}