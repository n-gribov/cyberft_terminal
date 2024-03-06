<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.css',
        'css/cybertheme.css',
        'css/glyphicons.min.css',
        'css/cybertheme-fix.css',
        'fonts/font-awesome-4.6.1/css/font-awesome.min.css',
        'css/bootstrap-select.min.css',
        'css/dashboard.css',
        'css/edm/templates/po-modal-view.css',
        'css/edm/templates/po-modal-form.css',
        'css/edm/templates/fcp-modal-form.css',
        'css/edm/fcp/style.css',
        'css/edm/fcst/style.css',
        'css/edm/fcvn/style.css'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
    ];
    public $js = [
        'js/controls.js',
        'js/plugins/bootstrap-select.js',
        'js/plugins/stickyhead.js',
        'js/documents.js',
        'js/mtmultiline.js',
        'js/edm/fcc-crr.js',
        'js/mtswitcher.js',
        'js/edm/templates/variables.js',
        'js/edm/templates/functions.js',
        'js/edm/templates/payment-order.js',
        'js/edm/templates/foreign-currency-payment.js',
        'js/edm/fcp/script.js',
        //'js/edm/fcst/script.js',
        'js/edm/fcvn/script.js',
        'js/documents-signer.js',
        'js/documents-status-tracker.js',
        'js/sticky-table-helper.js',
        'js/bootstrap-alert.js',
        'js/ajax-setup.js',
        'js/ajax-modals.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\form\ActiveFormAsset',
        'kartik\base\WidgetAsset',
        'kartik\select2\Select2Asset',
        'kartik\select2\ThemeKrajeeAsset',
        'kartik\select2\ThemeBootstrapAsset',
        'kartik\field\FieldRangeAsset',
        'kartik\datecontrol\DateControlAsset',
        'kartik\datecontrol\DateFormatterAsset',
        'yii\grid\GridViewAsset',
        'yii\widgets\ActiveFormAsset',
        'yii\validators\ValidationAsset',
        'yii\widgets\MaskedInputAsset',
        'addons\swiftfin\models\documents\mt\widgets\assets\TagAsset',
        'addons\swiftfin\models\documents\mt\widgets\assets\CollectionAsset',
        'addons\swiftfin\models\documents\mt\widgets\assets\ChoiceAsset',
        'common\widgets\InlineHelp\InlineHelpAsset'
    ];
}
