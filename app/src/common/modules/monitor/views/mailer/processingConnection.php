<h3><?= \Yii::t('monitor/mailer', 'Connection to processing error'); ?></h3>
<?= Yii::t('monitor/mailer', 'Check your STOMP and network settings for {terminals}', [
    'terminals' => $terminalsList
]); ?>