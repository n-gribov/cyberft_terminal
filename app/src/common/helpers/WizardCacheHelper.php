<?php

namespace common\helpers;

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use Yii;

class WizardCacheHelper
{
    public static function getPaymentOrderWizardCache()
    {
        $cacheKey = self::getCacheKey('payment-order');
        return self::getCachedData($cacheKey);
    }

    public static function deletePaymentOrderWizardCache()
    {
        $cacheKey = self::getCacheKey('payment-order');
        self::deleteCachedData($cacheKey);
    }

    public static function getFCCWizardCache()
    {
        $cacheKey = self::getCacheKey('fcc');

        return self::getCachedData($cacheKey);
    }

    public static function deleteFCCWizardCache()
    {
        $cacheKey = self::getCacheKey('fcc');
        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных СПД
     * @return array|mixed
     */
    public static function getCDIWizardCache()
    {
        $cacheKey = self::getCacheKey('cdi');

        return self::getCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных СВО
     */
    public static function deleteCDIWizardCache()
    {
        $cacheKey = self::getCacheKey('cdi');
        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных ПС
     * @return array|mixed
     */
    public static function getCRRWizardCache()
    {
        $cacheKey = self::getCacheKey('crr');

        return self::getCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных ПС
     */
    public static function deleteCRRWizardCache()
    {
        $cacheKey = self::getCacheKey('crr');

        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных покупки/продажи валюты
     * @return array|mixed
     */
    public static function getFCOWizardCache($type)
    {
        if ($type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE) {
            $cacheKey = self::getCacheKey('fco-purchase');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_SELL) {
            $cacheKey = self::getCacheKey('fco-sell');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $cacheKey = self::getCacheKey('fcst');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
            $cacheKey = self::getCacheKey('fcvn');
        } else {
            return null;
        }

        return self::getCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных покупки/продажи валюты
     */
    public static function deleteFCOWizardCache($type)
    {
        if ($type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE) {
            $cacheKey = self::getCacheKey('fco-purchase');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_SELL) {
            $cacheKey = self::getCacheKey('fco-sell');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $cacheKey = self::getCacheKey('fcst');
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
            $cacheKey = self::getCacheKey('fcvn');
        } else {
            return null;
        }

        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных валютного платежа
     * @return array|mixed
     */
    public static function getFCPWizardCache()
    {
        $cacheKey = self::getCacheKey('fcp');
        return self::getCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных валютного платежа
     */
    public static function deleteFCPWizardCache()
    {
        $cacheKey = self::getCacheKey('fcp');
        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных продажи с транзитного счета
     * @return array|mixed
     */
    public static function getFCSTWizardCache()
    {
        $cacheKey = self::getCacheKey('fcst');
        return self::getCachedData($cacheKey);
    }

    /**
     * Получение закэшированных данных поручений на конверсию валюты
     * @return array|mixed
     */
    public static function getFCVNWizardCache()
    {
        $cacheKey = self::getCacheKey('fcvn');
        return self::getCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных продажи с транзитного счета
     */
    public static function deleteFCSTWizardCache()
    {
        $cacheKey = self::getCacheKey('fcst');
        self::deleteCachedData($cacheKey);
    }

    /**
     * Удаление закэшированных данных поручений на конверсию валюты
     */
    public static function deleteFCVNWizardCache()
    {
        $cacheKey = self::getCacheKey('fcvn');
        self::deleteCachedData($cacheKey);
    }

    /**
     * Получение ключа кэша по типу документа
     * @param $type
     * @return string
     */
    public static function getCacheKey($type)
    {
        return "{$type}-wizard-" . Yii::$app->session->id;
    }

    /**
     * Получение закэшированных данных по ключу
     * @param $key
     * @return array|mixed
     */
    public static function getCachedData($key)
    {
        $cachedData = [];

        if (Yii::$app->cache->exists($key)) {
            $cachedData = Yii::$app->cache->get($key);
        }

        return $cachedData;
    }

    /**
     * Удаление закэшированных данных по ключу
     * @param $key
     */
    public static function deleteCachedData($key)
    {
        if (Yii::$app->cache->exists($key)) {
            Yii::$app->cache->delete($key);
        }
    }

    /**
     * Запись данных в кэш по ключу
     * @param $key
     * @param $data
     */
    public static function setCachedData($key, $data)
    {
        Yii::$app->cache->set($key, $data);
    }
}
