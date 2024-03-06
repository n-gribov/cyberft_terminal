<?php
use yii\helpers\Html;
?>

<div class="col-md-10 edm-templates">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <?= Yii::t('doc', 'Document Templates') ?>
            </div>
            <div class="pull-right">
                <span class="counters-label">
                    <?= Yii::t('edm', 'Banking') ?>
                </span>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?= Yii::t('edm', 'Template name') ?></th>
                        <th><?= Yii::t('doc', 'Recipient') ?></th>
                        <th><?= Yii::t('edm', 'Payment Purpose') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templates as $template) { ?>
                        <tr data-is-outdated="<?= $template['isOutdated'] ?>">
                            <td>
                                <?php

                                    echo Html::a($template['name'], '#', [
                                        'class' => 'edm-template-po-view-modal-btn',
                                        'data' => [
                                            'id' => $template['id'],
                                            'name' => $template['name']
                                        ]
                                    ]);

                                ?>
                            </td>
                            <td><?=$template['beneficiary'] ?></td>
                            <td><?=$template['paymentPurpose'] ?></td>
                            <td>
                                <a class="template-load-link" href="<?=$template['url']?>" data-is-outdated="<?= $template['isOutdated'] ?>">
                                    <span class="glyphicon glyphicon-open-file"></span>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4">
                            <?=Html::a(Yii::t('app/profile', 'Full List'), "/edm/payment-order-templates", [
                                'class' => 'templates-show-all'
                            ])?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

echo $this->render('@addons/edm/views/payment-order-templates/payment-order/_modalForm');
echo $this->render('@addons/edm/views/payment-order-templates/payment-order/_modalView');
echo $this->render('@addons/edm/views/payment-order-templates/_update-template-js');
