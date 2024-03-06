<?php

$script = <<< JS

    function printIframe(id) {
        var iframe = document.getElementById(id).contentWindow;

        var result = iframe.document.execCommand('print', false, null);

        if (!result) {
            iframe.print();
        }
    }

    $('$printBtn').off('click');
    $('$printBtn').on('click', function(e) {
        e.preventDefault();

        var iframeId = 'fastPrint-$iframeHash';
        var iframeIdStr = '#' + iframeId;
        
        if ($(iframeIdStr).length > 0) {
            $(iframeIdStr).remove();
        }
        
        $('body').append('<iframe id="'+ iframeId +'" style="width: 0; height:0; border: none;" src="$printUrl"></iframe>');

        $(iframeIdStr).load(function() {
            printIframe(iframeId);
        });
        
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
