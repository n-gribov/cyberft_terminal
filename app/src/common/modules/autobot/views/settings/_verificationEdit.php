<?php

use common\helpers\Html;
use yii\helpers\Url;
use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;

$sender = $data['sender'];
$currency = $data['currency'];
$signerList = $data['signerList'];
$rangeList = $data['rangeList'];

function renderConditions($conditions, $mode, $signerList, $prefix = [], $cond_id = 0)
{
    $andButtonOnClick = ($mode === 'and') ? 'insertConditionAjax' : 'insertBlockAjax';
    $orButtonOnClick = ($mode === 'and') ? 'insertBlockAjax' : 'insertConditionAjax';

    if (!empty($prefix)) {
        $fieldPrefix = 'f[' . implode('][', $prefix) . ']';
    } else {
        $fieldPrefix = 'f';
    }

    $count = 0;

    foreach($conditions as $cond) {
        if (!array_key_exists('value', $cond)) {
            // recursive block
            $new_prefix = $prefix;
            $new_prefix[] = $cond_id;
            $cond_id++;
            ?>
            <?php if ($count > 0) {
                echo '<span style="background:#4F8EDC;color:white;padding:2px 4px 0px 4px">' . Yii::t('app', $mode) . '</span>';
            }?>
            <div class="panel-body" id="cond_block_<?=implode('_', $new_prefix)?>">
                <?php
                $invMode = $mode == 'and' ? 'or' : 'and';
                $cond_id = renderConditions($cond, $invMode, $signerList, $new_prefix, $cond_id);
                ?>
            </div>
            <?php
            continue;
            //recursive block end
        }
        ?>
        <div class="logical_<?=$mode?>" id="cond_<?=$cond_id?>">
            <?php
            $condPrefix = $fieldPrefix . '[' . $cond_id . ']';
            if ($count > 0) {
                echo Yii::t('app', $mode);
            }
            ?>
            <?=
            Html::dropDownList(
                $condPrefix . '[value]',
                $cond['value'],
                $signerList
            );
            ?>
            <?=	Html::hiddenInput($condPrefix . '[op]', 'has') ?>
            <?=	Html::hiddenInput($condPrefix . '[attr]', 'documentFingerprints') ?>

            <input type="button" value="<?=Yii::t('app', 'and') . '...'?>" onClick="return <?=$andButtonOnClick?>(this, '<?=$mode?>')"/>
            <input type="button" value="<?=Yii::t('app', 'or') . '...'?>" onClick="return <?=$orButtonOnClick?>(this, '<?=$mode?>')"/>
            <?php if ($count > 0) :?>
                <a onClick="return deleteCondition(this)" href="javascript:;"><span class="glyphicon glyphicon-trash"></span></a>
            <?php endif ?>
        </div>
        <?php
        $cond_id++;
        $count++;
    }
    return $cond_id;
}
?>

<?php

// Получение наименования участника
$truncatedId = Address::truncateAddress($sender);
$participantData = BICDirParticipant::find()->where(['participantBIC' => $truncatedId])->one();

if ($participantData) {
    $senderTitle = $participantData->name;
} else {
    $senderTitle = $sender;
}

?>



<h3>Отправитель: <?=$senderTitle?><br/>
    Валюта: <?=$currency?></h3>
<div class="panel panel-primary">
    <?= Html::beginForm(Url::toRoute(['verification-update', 'sender' => $sender, 'currency' => $currency]), 'post') ?>
    <?php
        $cond_id = 0;
        foreach($rangeList as $rangeKey => $range) :
    ?>
        <div class="panel-body">
            <div class="form-group col-lg-5" style="clear:both">
                Диапазон:
                <input type="text" name="sumFrom[<?=$rangeKey?>]" value="<?=$range['sumFrom']?>"/>
                ...
                <input type="text" name="sumTo[<?=$rangeKey?>]" value="<?=$range['sumTo']?>"/>
                <?php
                if ($rangeKey != 0) {
                    echo Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-condition', 'id' => $rangeKey, 'sender' => $sender, 'currency' => $currency]));
                }
                ?>
            </div>
            <div style="clear:both" class="col-lg-3">
                Требуются подписи:
                <?php
                $condition = $range['condition'];

                if (!empty($condition)) {
                    $conditionStart = $condition;
                } else {
                    $conditionStart = [0 => [0 => ['value' => '']]];
                }
                $cond_id = renderConditions($conditionStart, 'or', $signerList, [$rangeKey], $cond_id);
                ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
<input type="hidden" value="" id="addRange" name="addRange"/>
<?php if (!count($rangeList)) : ?>
    <div class="alert alert-info"><?=Yii::t('app', 'No sum ranges are defined - use "Add Sum Range" button')?></div>
<?php else : ?>
    <input type="submit" value="<?=Yii::t('app', 'Save')?>" class="btn btn-success"/>
<?php endif ?>
<input type="submit" value="<?=Yii::t('app', 'Add Sum Range')?>" class="btn btn-success" onClick="return submitAddRange()"/>
<?= Html::endForm() ?>
</div>

<script language="javascript">

    function submitAddRange()
    {
        $('#addRange').val(1);
        return true;
    }

    /**
     * condition is added to the current block, with the same button logic as clicked condition,
     * no new block is created
     *
     * @param {Object} btn the clicked button
     * @param {String} logop the current logical op of the parent block
     * @returns {Boolean}
     */
    function insertConditionAjax(btn, logop)
    {
        var cond_block = (btn === null) ? $('#cond_block_0') : $(btn).parent().parent();
        var block_id = cond_block.attr('id');
        var prefix = getBlockPrefix(block_id);
        get_ajax_condition(block_id, logop, prefix, cond_id);
        cond_id++;
        return false;
    }

    /**
     * adds block of conditions including the condition that was clicked.
     * each embedded block flips button logic:
     * blocks added to 'and' become 'or' and vice versa
     *
     * @todo it is possible that all conditions will go inside blocks
     *		 and no new condition would be available to be added on the top level;
     *		 if (there are only blocks and no conditions) { add matching logop button below all blocks }
     * @param {Object} btn
     * @param {String} logop
     * @returns {Boolean}
     */
    function insertBlockAjax(btn, logop)
    {
        var cond = $(btn).parent();
        var cond_block = cond.parent();
        var block_id = cond_block.attr('id');

        var data = getCondData(cond);
        cond.detach();

        block_id += '_' + cond_id;
        cond_id++;

        var prefix = getBlockPrefix(block_id);
        var inv_logop = (logop === 'and') ? 'or' : 'and';

        var html =
            '<span style="background:#4F8EDC;color:white;padding:2px 4px 0px 4px">' + logops_translated[logop] + '</span>'
            + '<div class="panel-body" id="' + block_id + '">'
            + '</div>';
        cond_block.append(html);

        var inv_logop = (logop === 'and') ? 'or' : 'and';
        get_ajax_block(block_id, inv_logop, prefix, cond_id, data);
        cond_id += 2;

        return false;
    }

    function get_ajax_condition(block_id, logop, prefix, cond_id)
    {
        $.ajax({
            url: 'signer-select',
            method: 'post',
            data: { 'logop': logop, 'prefix': prefix, 'cond_id': cond_id },
            success: function(data) {
                ajax_success(data, block_id);
            },
            error: ajax_error
        });
        return false;
    }

    function get_ajax_block(block_id, logop, prefix, cond_id, cond_data)
    {
        $.ajax({
            url: 'signer-select',
            method: 'post',
            data: { 'logop': logop, 'prefix': prefix, 'cond_id': cond_id, 'cond_data':cond_data },
            success: function(data) {
                ajax_success(data, block_id);
            },
            error: ajax_error
        });
        return false;
    }

    function ajax_success(data, block_id) {
        var cond_block = $('#' + block_id);
        cond_block.append(data);
    }

    function ajax_error(data) {
        alert('error ' + JSON.stringify(data));
    }

    /**
     * @todo move condition out of block if it's the only one left; recursive delete block if empty
     * @param {Object} btn
     * @returns {String}
     */
    function deleteCondition(btn)
    {
        var cond = $(btn).parent();
        cond.detach();
        return false;
    }

    /**
     * retrieves currently entered data in condition inputs
     * @param {Object} cond
     * @returns {Object}
     */
    function getCondData(cond)
    {
        var cond_prefix = getCondPrefix(cond.attr('id'));
        var cond_block = cond.parent();
        var block_id = cond_block.attr('id');
        var prefix = getBlockPrefix(block_id);

        var attr_select = cond.children("select[name|='f" + prefix + cond_prefix + "[attr_select]']");
        var op_select = cond.children("select[name|='f" + prefix + cond_prefix + "[op_select]']");
        var value = cond.children("input[name|='f" + prefix + cond_prefix + "[value]']");
        if (value.length === 0) {
            value = cond.children("select[name|='f" + prefix + cond_prefix + "[value]']");
        }
        return {
            attr: $(attr_select[0]).val(),
            op: $(op_select[0]).val(),
            value: $(value[0]).val()
        };
    }

    function getBlockPrefix(block_id)
    {
        var chunks = block_id.substr(11).split('_');
        var res = '[' + chunks.join('][') + ']';
        return res;
    }

    function getCondPrefix(cond_id)
    {
        return '[' + cond_id.substr(5) + ']';
    }

    var logops_translated = {
        'and':'<?=Yii::t('app', 'and')?>',
        'or':'<?=Yii::t('app', 'or')?>'
    };
    var cond_id = <?=$cond_id?>;
    //  $('meta[name="csrf-token"]').attr('content');
    //  $('meta[name="csrf-param"]').attr('content');
    //	insertConditionAjax(null, 'and');

</script>