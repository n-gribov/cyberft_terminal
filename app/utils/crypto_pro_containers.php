<?php
$output = [];
exec('sudo -u www-data /opt/cprocsp/bin/amd64/csptest -keyset -enum_cont -verifycontext -fqcn', $output);
foreach($output as $line) {
    if(!empty($line{0})) {
        if ($line{0} !== '\\') {
            continue;
        }
    }
    echo $line . "\n";
    exec("sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -inst -cont '{$line}'");
    usleep(250000);
}
?>
