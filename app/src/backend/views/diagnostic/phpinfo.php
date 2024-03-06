<?php
$this->title = Yii::t('app/diagnostic', 'Environment');

/**
 * @var $this View
 */
?>
<?=$this->render('_submenu');?>

<div class="panel-heading">
    <h4>Phpinfo</h4>
</div>
<div class="panel-body">
    <div class="tab-content">
        <style type="text/css">
            .center {text-align: center}
            .center table { margin-left: auto; margin-right: auto; text-align: left;}
            .center th { text-align: center !important; }
            .center td,  .center th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
            .center td {font-size: 12px; padding: 5px;}
            .center h1 {font-size: 150%;}
            .center h2 {font-size: 125%;}
            .p {text-align: left;}
            .e {background-color: #ccccff; font-weight: bold; color: #000000;}
            .h {background-color: #9999cc; font-weight: bold; color: #000000;}
            .v {background-color: #cccccc; color: #000000;}
            .vr {background-color: #cccccc; text-align: right; color: #000000;}
            .center img {float: right; border: 0px;}
            .center hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
        </style>
        <?php
            ob_start();
            phpinfo();
            $data = ob_get_clean();
            $data = preg_replace("/\<title\>.*<\/title\>/i",'',$data);
            $data = preg_replace("/\<style.*<\/style\>/sUi",'',$data);
            $data = strtr($data,[
                ','	=> ', ',
                '255B' => "255B\n",
                '&' => "&\n",
            ]);
        ?>
        <?=$data?>
    </div>
</div>


