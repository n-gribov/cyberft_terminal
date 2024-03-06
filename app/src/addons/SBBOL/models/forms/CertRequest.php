<?php

namespace addons\SBBOL\models\forms;

use common\base\Model;
use Yii;

/**
 * Форма запроса нового сертификата
 * Class CertRequest
 * @package addons\SBBOL\models\forms
 */
class CertRequest extends Model
{
    public $commonName;
    public $organization;
    public $organizationUnit;
    public $locality;
    public $country;
    public $email;
    public $position;

    public function attributeLabels()
    {
        return [
            'commonName' => Yii::t('app/sbbol', 'Common name'),
            'organization' => Yii::t('app/sbbol', 'Organization'),
            'organizationUnit' => Yii::t('app/sbbol', 'Organization unit'),
            'locality' => Yii::t('app/sbbol', 'Locality'),
            'country' => Yii::t('app/sbbol', 'Country'),
            'email' => Yii::t('app/sbbol', 'Email'),
            'position' => Yii::t('app/sbbol', 'Position'),
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'commonName', 'organization', 'organizationUnit',
                    'locality', 'country', 'email', 'position'
                ], 'required'
            ],
            ['email', 'email'],
            [
                // Валидация на наличие только латинских символов
                [
                    'country',
                ],
                'match', 'pattern' => '/^([a-z \-]*)$/ui',
                'message' => Yii::t('app/autobot', 'Allowed only latin characters')
            ],
        ];
    }
}