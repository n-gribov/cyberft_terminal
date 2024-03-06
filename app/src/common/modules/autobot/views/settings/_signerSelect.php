<?php

use yii\helpers\Html;

$andButtonOnClick = ($logop === 'and') ? 'insertConditionAjax' : 'insertBlockAjax';
$orButtonOnClick = ($logop === 'and') ? 'insertBlockAjax' : 'insertConditionAjax';

?>

<?php if (!empty($cond_data)) : ?>

<div class="logical_<?=$logop?>" id="cond_<?=$cond_id?>">
    <?php
    $condPrefix = 'f' . $prefix . '[' . $cond_id . ']';
    echo Html::dropDownList(
        $condPrefix . '[value]',
        $cond_data['value'],
        $signerList
    );
    ?>
    <?=	Html::hiddenInput($condPrefix . '[op]', 'has') ?>
    <?=	Html::hiddenInput($condPrefix . '[attr]', 'documentFingerprints') ?>

    <input type="button" value="<?=Yii::t('app', 'and') . '...'?>" onClick="return <?=$andButtonOnClick?>(this, '<?=$logop?>')"/>
    <input type="button" value="<?=Yii::t('app', 'or') . '...'?>" onClick="return <?=$orButtonOnClick?>(this, '<?=$logop?>')"/>
    <a href="javascript:;" onClick="return deleteCondition(this)"><span class="glyphicon glyphicon-trash"></span></a>

    <?php
    $cond_id++;
    endif;
    ?>

    <div class="logical_<?=$logop?>" id="cond_<?=$cond_id?>">
        <?= Yii::t('app', $logop) ?>
        <?php
        $condPrefix = 'f' . $prefix . '[' . $cond_id . ']';
        echo Html::dropDownList(
            $condPrefix . '[value]',
            null,
            $signerList
        );
        ?>
        <?=	Html::hiddenInput($condPrefix . '[op]', 'has') ?>
        <?=	Html::hiddenInput($condPrefix . '[attr]', 'documentFingerprints') ?>

        <input type="button" value="<?=Yii::t('app', 'and') . '...'?>" onClick="return <?=$andButtonOnClick?>(this, '<?=$logop?>')"/>
        <input type="button" value="<?=Yii::t('app', 'or') . '...'?>" onClick="return <?=$orButtonOnClick?>(this, '<?=$logop?>')"/>
        <a href="javascript:;" onClick="return deleteCondition(this)"><span class="glyphicon glyphicon-trash"></span></a>
    </div>
