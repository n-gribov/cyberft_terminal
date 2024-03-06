<?php

namespace addons\sbbol2\authClient;

use Yii;
use yii\authclient\Collection;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class AuthAction extends \yii\authclient\AuthAction
{
    /**
     * @var Collection|null
     */
    public $authCollectionInstance;

    public function run()
    {
        if ($this->authCollectionInstance === null || !$this->authCollectionInstance instanceof Collection) {
            Yii::error('Value of authCollectionInstance is empty or is invalid');
            throw new InvalidConfigException();
        }

        if (!isset($_GET[$this->clientIdGetParamName]) || empty($_GET[$this->clientIdGetParamName])) {
            throw new NotFoundHttpException();
        }

        $clientId = $_GET[$this->clientIdGetParamName];
        /* @var $collection \yii\authclient\Collection */
        $collection = $this->authCollectionInstance;
        if (!$collection->hasClient($clientId)) {
            throw new NotFoundHttpException("Unknown auth client '{$clientId}'");
        }
        $client = $collection->getClient($clientId);

        return $this->auth($client);
    }
}
