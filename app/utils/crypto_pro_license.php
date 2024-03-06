<?php
$output = [];

exec('sudo -u www-data /opt/cprocsp/sbin/amd64/cpconfig -license -view', $output);
echo $output[1];
?>


