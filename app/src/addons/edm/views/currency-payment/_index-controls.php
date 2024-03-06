<?php

use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\CurrencyPayment\CurrencyPaymentSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SelectedDocumentsCountLabel;
use common\widgets\documents\ShowDeletedDocumentsCheckbox;
use yii\web\View;

/** @var View $this */
/** @var CurrencyPaymentDocumentSearch|CurrencyPaymentSearch $filterModel */
/** @var bool $userCanCreateDocuments */
/** @var bool $userCanDeleteDocuments */
/** @var string $entriesSelectionCacheKey */

?>

<div class="controls-block">
    <div class="pull-left">
        <?php if ($userCanCreateDocuments): ?>
            <?= $this->render('@addons/edm/views/documents/_fcoCreateButton', ['wizardType' => ForeignCurrencyOperationFactory::OPERATION_PAYMENT]) ?>
        <?php endif; ?>
        <?php if ($userCanDeleteDocuments): ?>
            <?= DeleteSelectedDocumentsButton::widget([
                'checkboxesSelector' => '.delete-checkbox, .select-on-check-all',
                'entriesSelectionCacheKey' => $entriesSelectionCacheKey,
            ]) ?>
            <?= SelectedDocumentsCountLabel::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']); ?>
        <?php endif; ?>
    </div>
    <?= $this->render('_search') ?>
    <?= ShowDeletedDocumentsCheckbox::widget(['filterModel' => $filterModel]) ?>
</div>

<?php
$this->registerCss(<<<CSS
    .controls-block {
        margin-top: 15px;
    }
    .controls-block .btn {
        margin-right: 0.5em;
    }
    .show-deleted-block {
        margin-top: 10px;
    }
CSS
);