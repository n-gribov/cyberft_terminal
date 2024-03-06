<style>
    table.signlist th, td { padding: 0 0.5em 0 0.5em }
</style>
<div id="signList">
    <table class="table-bordered signlist" style="min-width: 500px">
        <tr>
            <th colspan="3" style="text-align: center;"><?= Yii::t('doc', 'List of document signatures') ?></th>
        </tr>
        <tr>
            <th><?= Yii::t('app', 'Signer') ?></th>
            <th><?= Yii::t('app/cert', 'Certificate Thumbprint') ?></th>
            <th><?= Yii::t('app/message', 'Signing Time') ?></th>
        </tr>
        <?php foreach ($signatures as $value) : ?>
            <tr>
                <td><?= $value['name'] ?></td>
                <td><?= $value['fingerprint'] ?></td>
                <td><?= isset($value['signingTime']) ? $value['signingTime'] : '' ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
