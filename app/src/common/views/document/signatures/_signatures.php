<strong><?= Yii::t('doc', 'Document signatures') ?></strong>
<div id="signList">
    <table class="table-bordered" style="min-width: 500px;">
        <tr>
            <th><?= Yii::t('doc', 'Owner') ?></th>
            <th><?= Yii::t('doc', 'Post') ?></th>
            <th><?= Yii::t('app/message', 'Signing Time') ?></th>
        </tr>
        <?php foreach ($signatures as $value) : ?>
            <tr>
                <td><?= $value['name']; ?></td>
                <td><?= $value['post']; ?></td>
                <td><?= isset($value['signingTime']) ? $value['signingTime'] : '' ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<br/>
