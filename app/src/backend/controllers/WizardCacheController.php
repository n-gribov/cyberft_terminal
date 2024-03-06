<?php

namespace backend\controllers;

use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyConversion;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentType;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencySellTransit;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\ISO20022\models\form\WizardForm;
use common\base\Controller;
use common\helpers\Uuid;
use common\helpers\WizardCacheHelper;
use Yii;
use yii\filters\AccessControl;

class WizardCacheController extends Controller
{
    private $cachedMsg = 'Cached';
    private $emptyDataMsg = 'There is no data to cache';
    private $cacheFailedMsg = 'Cache failed';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionPaymentOrder()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('payment-order');

        $post = Yii::$app->request->post('PaymentOrderType');

        if (empty($post)) {
            return $this->emptyDataMsg;
        }

        $cachedData = new PaymentOrderType($post);

        WizardCacheHelper::setCachedData($cacheKey, $cachedData);

        return $this->cachedMsg;
    }

    private function processCacheResult($cacheKey, $cachedData)
    {
        if ($cachedData) {
            WizardCacheHelper::setCachedData($cacheKey, $cachedData);

            return $this->cachedMsg;
        }

        WizardCacheHelper::deleteCachedData($cacheKey);

        return $this->emptyDataMsg;
    }

    public function actionFcc()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('fcc');

        $formData = Yii::$app->request->post('ForeignCurrencyOperationInformationExt');
        $operation = Yii::$app->request->post('ForeignCurrencyOperationInformationItem');
        $deleteOperation = Yii::$app->request->post('DeleteOperation');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($formData) {
            $cachedData['model'] = new ForeignCurrencyOperationInformationExt($formData);
        }

        if ($operation) {
            $uuid = Yii::$app->request->post('uuid');
            if ($uuid == '') {
                $uuid = Uuid::generate();
            }

            $cachedData['operations'][$uuid] = new ForeignCurrencyOperationInformationItem($operation);
        }

        if ($deleteOperation) {
            if (isset($cachedData['operations'][$deleteOperation])) {
                unset($cachedData['operations'][$deleteOperation]);
            }
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionCdi()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('cdi');

        $formData = Yii::$app->request->post('ConfirmingDocumentInformationExt');
        $document = Yii::$app->request->post('ConfirmingDocumentInformationItem');
        $deleteDocument = Yii::$app->request->post('DeleteDocument');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($formData) {
            $cachedData['model'] = new ConfirmingDocumentInformationExt($formData);
        }

        if ($document) {
            $uuid = Yii::$app->request->post('uuid');
            if ($uuid == '') {
                $uuid = Uuid::generate();
            }
            $cachedData['documents'][$uuid] = new ConfirmingDocumentInformationItem($document);
        }

        if ($deleteDocument) {
            if (isset($cachedData['documents'][$deleteDocument])) {
                unset($cachedData['documents'][$deleteDocument]);
            }
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionCrr()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('crr');

        $form = Yii::$app->request->post('ContractRegistrationRequestExt');
        $nonresident = Yii::$app->request->post('ContractRegistrationRequestNonresident');
        $tranche = Yii::$app->request->post('ContractRegistrationRequestTranche');
        $paymentSchedule = Yii::$app->request->post('ContractRegistrationRequestPaymentSchedule');

        $uuid = Yii::$app->request->post('uuid') ?? Uuid::generate();
        $delete = Yii::$app->request->post('Delete');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($form) {
            $cachedData['model'] = new ContractRegistrationRequestExt($form);
        }

        if ($nonresident) {
            $data = new ContractRegistrationRequestNonresident($nonresident);
            $key = $data->isCredit ? 'nonresidentsCredit' : 'nonresidents';
            $cachedData[$key][$uuid] = $data;
        }

        if ($tranche) {
            $cachedData['tranches'][$uuid] = new ContractRegistrationRequestTranche($tranche);
        }

        if ($paymentSchedule) {
            $cachedData['paymentSchedule'][$uuid] = new ContractRegistrationRequestPaymentSchedule($paymentSchedule);
        }

        if ($delete) {
            if (isset($cachedData[$delete['type']][$delete['uuid']])) {
                unset($cachedData[$delete['type']][$delete['uuid']]);
            }
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionFco()
    {
        $post = Yii::$app->request->post();
        $type = new ForeignCurrencyOperationType();
        $type->load($post);

        if ($type->operationType == ForeignCurrencyOperationFactory::OPERATION_PURCHASE) {
            $cacheKey = WizardCacheHelper::getCacheKey('fco-purchase');
        } else if ($type->operationType == ForeignCurrencyOperationFactory::OPERATION_SELL) {
            $cacheKey = WizardCacheHelper::getCacheKey('fco-sell');
        } else {
            return $this->cacheFailedMsg;
        }

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);
        $cachedData['model'] = $type;

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionFcp()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('fcp');

        $form = Yii::$app->request->post('ForeignCurrencyPaymentType');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($form) {
            $cachedData['model'] = new ForeignCurrencyPaymentType($form);
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionFcst()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('fcst');

        $form = Yii::$app->request->post('ForeignCurrencySellTransit');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($form) {
            $cachedData['model'] = new ForeignCurrencySellTransit($form);
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionFcvn()
    {
        $cacheKey = WizardCacheHelper::getCacheKey('fcvn');

        $form = Yii::$app->request->post('ForeignCurrencyConversion');

        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($form) {
            $cachedData['model'] = new ForeignCurrencyConversion($form);
        }

        return $this->processCacheResult($cacheKey, $cachedData);
    }

    public function actionIsoFreeFormat()
    {
        $cacheKey =  'ISO20022/wizard/doc-' . Yii::$app->session->id;
        $form = Yii::$app->request->post('WizardForm');
        $cachedData = WizardCacheHelper::getCachedData($cacheKey);

        if ($cachedData) {
            $cachedData->setAttributes($form);
        } else {
            $cachedData = new WizardForm($form);
        }

        WizardCacheHelper::setCachedData($cacheKey, $cachedData);

        return $this->cachedMsg;
    }

    public function actionClearFcoCache()
    {
        $documents = ['fco-purchase', 'fco-sell', 'fcp', 'fcst', 'fcvn'];

        foreach ($documents as $item) {
            $key = WizardCacheHelper::getCacheKey($item);
            WizardCacheHelper::deleteCachedData($key);
        }
    }

}