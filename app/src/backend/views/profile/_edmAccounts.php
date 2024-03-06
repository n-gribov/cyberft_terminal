<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\DocumentPermission;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \addons\edm\models\EdmPayerAccount[] $accounts */

$userCanCreateStatementRequests = Yii::$app->user->can(
    DocumentPermission::CREATE,
    ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::STATEMENT]
);

$shouldShowOrganization = Yii::$app->user->identity->disableTerminalSelect;

?>
<div class="col-md-12 edm-accounts">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <?= Yii::t('edm', 'Account Information') ?>
            </div>
            <?php if ($userCanCreateStatementRequests) : ?>
                <div class="pull-right">
                    <a href="/edm/edm-payer-account/send-request-all-accounts" class="btn btn-request-all-totals btn-success">
                        <?= Yii::t('edm', 'Request balances') ?>
                    </a>
                </div>
            <?php endif ?>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <?php if ($shouldShowOrganization): ?>
                        <th><?= Yii::t('edm', 'Organization') ?></th>
                    <?php endif ?>
                    <th><?= Yii::t('edm', 'Account number') ?></th>
                    <th><?= Yii::t('edm', 'Bank') ?></th>
                    <th><?= Yii::t('edm', 'Currency') ?></th>
                    <th><?= Yii::t('edm', 'Title') ?></th>
                    <th class="text-right"><?= Yii::t('edm', 'Current Balance') ?></th>
                    <th class="text-right"><?= Yii::t('edm', 'Actuality Date') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($accounts as $account) : ?>
                    <tr>
                        <?php if ($shouldShowOrganization): ?>
                            <td><?= Html::encode($account->edmDictOrganization->name) ?></td>
                        <?php endif ?>
                        <td>
                            <a href="<?= Url::to(['/edm/edm-payer-account/view', 'id' => $account->id]) ?>">
                                <?= Html::encode($account->number) ?>
                            </a>
                        </td>
                        <td><?= Html::encode($account->bank->name) ?></td>
                        <td><?= Html::encode($account->edmDictCurrencies->name) ?></td>
                        <td><?= Html::encode($account->name) ?></td>
                        <td class="text-right"><?= $account->balance ? number_format($account->balance->balance, 2, '.', ' ') : '' ?></td>
                        <td class="text-right"><?= $account->balance ? $account->balance->updateDate : '' ?></td>
                        <td>
                            <a href="<?= Url::toRoute(['/edm/documents/statement', 'StatementSearch' => ['accountNumber' => $account->number]]) ?>"><?= Yii::t('edm', 'Statements') ?></a>
                        </td>
                        <td>
                            <?php if ($userCanCreateStatementRequests): ?>
                                <a href="#" class="statement-request" data-id="<?= $account->id ?>"><?= Yii::t('edm', 'Statement request') ?></a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="statement-request-block"></div>

<?php

// Отображение формы запроса выписки по отдельному счету
$script = <<<JS
    $('.statement-request').on('click', function(e) {
        var accountId = $(this).data('id');

        $.ajax({
            url: '/profile/render-statement-request',
            type: 'get',
            data: {accountId: accountId},
            success: function(answer){
                $('.statement-request-block').html(answer);
                $('#sendRequestForm').modal('show');
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);


$this->registerCss('
    a.btn-request-all-totals {
        text-decoration: none;
    }
');
