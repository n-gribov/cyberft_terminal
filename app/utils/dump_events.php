<?php
$base = '../src/common/events/user/';
$dir = opendir($base);
$csv = fopen('events_' . date('Ymd') . '.csv', 'w');
fputs($csv, "Код события;Наименование события\n");
while($filename = readdir($dir)) {
    $path = $base . $filename;
    if (!is_file($path)) {
        continue;
    }

    $code = file_get_contents($path);
    $pos = strpos($code, 'getCodeLabel()');
    if ($pos === false) {
        continue;
    }

    $pos = strpos($code, 'return', $pos);
    $endPos = strpos($code, ';', $pos);
    $label = trim(substr($code, $pos + 7, $endPos - 7 - $pos));

    $pos = strpos($label, 'Yii::t(');
    if ($pos !== false) {
        $pos = strpos($label, ",", 8);
        $label = trim(substr($label, $pos + 1));
        $label = rtrim($label, ')');
    }

    $eventCode = substr($filename, 0, strlen($filename) - 9);
    echo "filename: $filename, event code: $eventCode, label: $label\n";
    fputs($csv, "$eventCode;$label\n");
}

fclose($csv);
?>