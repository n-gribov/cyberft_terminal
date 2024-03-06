<?php

use common\helpers\Html;

?>

<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= Yii::t('monitor/mailer', 'Expired certificates notification') ?></h4>
            </div>

            <div class="modal-body">
                <?php foreach($certs as $group => $type): ?>
                    <h3><?=$group?>:</h3>

                    <?php foreach($type as $label => $content): ?>
                        <h4><?=$label?></h4>

                        <table class="table">
                            <colgroup>
                                <col width="10%">
                                <col width="35%">
                                <col width="35%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th><?= Yii::t('app/terminal', 'Terminal ID') ?></th>
                                <th><?= Yii::t('app/cert', 'Organization') ?></th>
                                <th><?= Yii::t('app/cert', 'Owner Name') ?></th>
                                <th><?= Yii::t('app/cert', 'Certificate Thumbprint') ?></th>
                                <th><?= Yii::t('app/cert', 'Expires on')?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($content as $cert): ?>
                                <tr>
                                    <td><?= Html::encode($cert['terminal']) ?></td>
                                    <td><?= Html::encode($cert['terminalName']) ?></td>
                                    <td><?= Html::encode($cert['owner']) ?></td>
                                    <td><?= Html::encode($cert['fingerprint']) ?></td>
                                    <td><?= Html::encode($cert['date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php endforeach; ?>
                <?php endforeach; ?>

                <p style="font-weight: bold">

                    <a href="http://download.cyberft.ru/Documentation/CFT_creating_keys_manual.pdf"
                       target="_blank"><?= Yii::t('app/cert', 'Create and Update Keys User Manual') ?></a>
                </p>
                <p style="font-weight: bold">
                    <?= Yii::t('app/cert', 'Please direct your questions to the support email') ?>:
                    <a href="mailto:support@cyberft.ru">support@cyberft.ru</a>
                </p>
                
                <?php 
                
                if(isset($isPlatinaBik) && $isPlatinaBik) { ?>
                
                <p style="font-weight: bold; color: red"> <?= Yii::t('app/cert', 'Attention! Important information for the customers of Platina CB LLC.') ?> </p>

                <p style="font-weight: bold"><?= Yii::t('app/cert', 'Registration of the Act on the recognition of electronic signature for a new term
                     is made only for the Clients who timely submitted documents on an annual
                     update. You can check the update date with your supervising manager.')?></p>

                <p style="font-weight: bold"><a href="https://www.platina.ru/legal/opens.shtml"
                                                target="_blank">
                        <?= Yii::t('app/cert', 'Detailed information') ?></a></p>
                    
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>
