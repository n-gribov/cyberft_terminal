<?php

namespace common\modules\certManager\models;

use Yii;
use yii\base\Model;

/**
 * User key form model class
 *
 * @property string $countryName          Key country name
 * @property string $stateOrProvinceName  Key state or province name
 * @property string $localityName         Key locality name
 * @property string $organizationName     Key organization name
 * @property string $commonName           Key common name
 */
class UserKeyForm extends Model
{
    /**
     * @var string $countryName Key country name
     */
    public $countryName;

    /**
     * @var string $stateOrProvinceName Key state or province name
     */
    public $stateOrProvinceName;

    /**
     * @var string $localityName Key locality name
     */
    public $localityName;

    /**
     * @var string $organizationName Key organization name
     */
    public $organizationName;

    /**
     * @var string $commonName Key common name
     */
    public $commonName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $certSettings              = Yii::$app->settings->get('Cert');
        $this->countryName         = $certSettings->countryName;
        $this->stateOrProvinceName = $certSettings->stateOrProvinceName;
        $this->localityName        = $certSettings->localityName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countryName', 'stateOrProvinceName', 'localityName', 'organizationName', 'commonName'], 'required'],
            [['countryName', 'stateOrProvinceName', 'localityName', 'organizationName', 'commonName'], 'string', 'max' => 64],
            ['countryName', 'match', 'pattern' => '/^[a-zA-Z]{2}/i', 'message' => Yii::t('app/validation', 'The «{attribute}» should contain only latin letters')],
            [['stateOrProvinceName', 'localityName'], 'match', 'pattern' => '/^[a-zA-Zа-яА-Я]{2,}[a-zA-Zа-яА-Я-.,/]{0,}/i', 'message' => Yii::t('app/validation', 'The «{attribute}» must begin with a letter and should contain only latin letters, space and symbols(,.-/)')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'countryName'         => Yii::t('app/autobot', 'Country name'),
            'stateOrProvinceName' => Yii::t('app/autobot', 'State or province'),
            'localityName'        => Yii::t('app/autobot', 'Locality name'),
            'organizationName'    => Yii::t('app/autobot', 'Organization name'),
            'commonName'          => Yii::t('app/autobot', 'Common name'),
        ];
    }

}