<?php

namespace addons\edm\controllers\actions;

use addons\edm\helpers\EdmHelper;
use addons\edm\helpers\FormatDetector1C;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use common\helpers\ControllerCache;
use common\helpers\UserHelper;
use Exception;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ImportPaymentRegisterAction extends Action
{
    private $terminalId;
    private $typeModels = [];
    private $models = [];

    public function run()
    {
        $defaultUserTerminal = Yii::$app->terminals->getPrimaryTerminal();
        $this->terminalId = $defaultUserTerminal->id;

        if (empty($this->terminalId)) {
            return $this->showImportError(Yii::t('edm', 'Could not find default terminal'));
        }

        $typeModelsAreLoaded = $this->loadTypeModels();
        if (!$typeModelsAreLoaded) {
            $this->logInvalidDocument();

            return $this->showImportError(Yii::t('edm', 'Uploaded file does not conform to 1CClientBankExchange format'));
        }

        if ($this->hasInvalidTypeModels()) {
            return $this->showOrdersErrors();
        }

        $modelsAreValid = $this->initializeModels();

        if (!$modelsAreValid) {
            return $this->showOrdersErrors();
        }

        if (!$this->allOrdersHaveSamePayerAccount()) {
            $this->logInvalidDocument();

            return $this->showImportError(Yii::t('edm', 'Payment orders must have same payer account'));
        }

        if (!$this->isValidContractorAccountExist($this->getPayerAccount())) {
            $this->logInvalidDocument();
            $errorMessage = Yii::t(
                'edm',
                'Account {account} is not found in contractors dictionary',
                ['account' => $this->getPayerAccount()]
            );

            return $this->showImportError($errorMessage);
        }

        $this->saveModels();
        return $this->savePaymentRegister();
    }

    private function savePaymentRegister()
    {
        $data = [];

        $paymentOrderCache = new ControllerCache('paymentOrders');

        foreach($this->models as $model) {
            $data['entries'][$model->id] = true;
        }

        $paymentOrderCache->set($data);

        return $this->controller->redirect(['/edm/payment-register/create?removeOrdersOnFailure=1']);
    }

    private function loadTypeModels()
    {
        $file = UploadedFile::getInstanceByName('register_file');

        try {
            $this->typeModels = FormatDetector1C::detect($file->tempName, ['skipInvalidDocuments' => false]);
        } catch (Exception $exception) {
            Yii::info('Failed parsing register file, caused by: ' . $exception->getMessage());
        }

        if (!$this->typeModels) {
            $this->typeModels = [];
        } else if (!is_array($this->typeModels)) {
            $this->typeModels = [$this->typeModels];
        }

        return count($this->typeModels) > 0;
    }

    private function initializeModels()
    {
        $hasInvalidModels = false;

        // Проверка наличия дубликатов платежных поручений
        $dataExists = EdmHelper::checkPaymentOrderDuplicate($this->typeModels);

        if ($dataExists !== false) {
            $hasInvalidModels = true;

            foreach ($dataExists as $typeModel) {
                $typeModel->addError('number',
                    Yii::t('edm', 'Failed to create document {num} - number was used before', ['num' => $typeModel->number])
                );
            }
        } else {
            foreach ($this->typeModels as $typeModel) {
                if (!$typeModel->validate()) {
                    $hasInvalidModels = true;
                    $this->logInvalidDocument();

                    continue;
                }

                $paymentOrder = new PaymentRegisterPaymentOrder();
                $paymentOrder->loadFromTypeModel($typeModel);
                $paymentOrder->terminalId = $this->terminalId;

                if (!$paymentOrder->validate()) {
                    $this->logInvalidDocument();
                    $hasInvalidModels = true;
                }

                $this->models[] = $paymentOrder;
            }
        }

        return !$hasInvalidModels;
    }

    private function allOrdersHaveSamePayerAccount()
    {
        $account = $this->getPayerAccount();

        foreach ($this->models as $model) {
            if ($account != $model->payerAccount) {
                return false;
            }
        }

        return true;
    }

    private function isValidContractorAccountExist($accountNumber)
    {
        // Получаем организации, доступные текущему пользователю
        $queryOrganizations = Yii::$app->terminalAccess->query(DictOrganization::className());
        $queryOrganizations->select('id')->asArray();
        $organizations = $queryOrganizations->all();

        $account = EdmPayerAccount::find()
            ->with(['bank'])
            ->where([
                'organizationId' => ArrayHelper::getColumn($organizations, 'id'),
                'number' => $accountNumber
            ])
            ->one();

        return $account != null && $account->bank != null && !empty($account->bank->terminalId);
    }

    private function logInvalidDocument()
    {
        Yii::$app->monitoring->log('edm:InvalidDocument');
    }

    private function saveModels()
    {
        $savedModelsCount = 0;

        foreach ($this->models as $model) {
            if ($model->save()) {
                $savedModelsCount++;
                Yii::$app->monitoring->log(
                    'user:createDocument',
                    'PaymentRegisterPaymentOrder',
                    $model->id,
                    [
                        'userId' => Yii::$app->user->id,
                        'docType' => 'edmPaymentOrder',
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );
            }
        }

        return $savedModelsCount;
    }

    private function getPayerAccount()
    {
        return empty($this->models) ? null : $this->models[0]->payerAccount;
    }

    private function showOrdersErrors()
    {
        $errorsHtml = $this->controller->renderPartial(
            '_paymentRegisterPaymentOrdersErrors',
            ['models' => array_merge($this->typeModels, $this->models)]
        );

        return $this->showImportError($errorsHtml);
    }

    private function showImportError($errorMessage)
    {
        Yii::$app->session->addFlash(
            'warning',
            Yii::t('edm', 'Unable to import register') . '<br>' . $errorMessage
        );

        return $this->controller->redirect(Yii::$app->request->referrer);
    }

    private function hasInvalidTypeModels()
    {
        foreach ($this->typeModels as $typeModel) {
            if ($typeModel->hasErrors()) {
                return true;
            }
        }

        return false;
    }

}
