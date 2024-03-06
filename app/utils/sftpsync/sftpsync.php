<?php
$iniFilePath = 'sftpsync.ini';

if (isset($argv[1])) {
    $iniFilePath = $argv[1];
}

if (!is_file($iniFilePath) || !is_readable($iniFilePath)) {
    echo "Ini file is not found: $iniFilePath\n";

    exit(1);
}

//echo 'Start ' . date('d.m.Y H:i:s') . " using ini file: $iniFilePath\n";

$ini = parse_ini_file($iniFilePath, true);

$requiredSettings = [
    'host' => true,
    'port' => true,
    'login' => true,
    'privateKeyPath' => true,
    'publicKeyPath' => true,
    'password' => false,
    'protocol' => false,
];

$protocol = isset($ini['protocol']) ? strtoupper($ini['protocol']) : 'SFTP';

if (!in_array($protocol, ['FTP', 'SFTP', 'LFTP'])) {
    echo 'Unknown protocol: ' . $protocol . "\n";

    exit(1);
}

$password = $ini['password'] ?: false;
if ($password) {
    $requiredSettings['privateKeyPath'] = false;
    $requiredSettings['publicKeyPath'] = false;
    $requiredSettings['password'] = true;
}

foreach($requiredSettings as $key => $required) {
    if ($required && empty($ini[$key])) {
        echo 'Required argument is missing in ini file: ' . $key . "\n";

        exit(1);
    }
}

$import = [];
$export = [];

foreach($ini as $key => $value) {
    if (substr($key, 0, 7) == 'import ') {
        $import[] = $value;
    } else if (substr($key, 0, 7) == 'export ') {
        $export[] = $value;
    } else {
        if (!array_key_exists($key, $requiredSettings)) {
            echo "Unrecognized section: $key\n";
        }
        $value = false;
    }
    if ($value && (!isset($value['localFolder']) || !isset($value['sftpFolder']))) {
        echo "Folder structure is not configured properly in section [$key]\n";

        exit(1);
    }
}

if (empty($import) && empty($export)) {
    echo "No import or export sections defined, check ini file\n";

    exit(1);
}

if (!$password) {
    $publicKeyPath = $ini['publicKeyPath'];

    if (!is_file($publicKeyPath) || !is_readable($publicKeyPath)) {
        echo "Cannot read public key file: $publicKeyPath\n";

        exit(1);
    }

    $privateKeyPath = $ini['privateKeyPath'];

    if (!is_file($privateKeyPath) || !is_readable($privateKeyPath)) {
        echo "Cannot read private key file: $privateKeyPath\n";

        exit(1);
    }
}

if ($protocol == 'LFTP') {
    processLFTP($ini, $export, $import);
} else {
    $handle = getHandle($protocol, $ini);

    if (!$handle) {
        echo "Cannot start $protocol session\n";

        exit(1);
    }

    foreach($export as $params) {
        processExport($sftp, $params);
    }

    foreach($import as $params) {
        processImport($handle, $params);
    }
}

exit(0);

function processExport($handle, $params)
{
    $localFolder = $params['localFolder'];

    if (!is_dir($localFolder)) {
        echo "Export folder does not exist: $exportFolder\n";

        return false;
    }

    $sftpFolder = $params['sftpFolder'];
    $sftpResourceId = intval($handle);
    $sftpExportPath = 'ssh2.sftp://' . $sftpResourceId . '/' . $sftpFolder;
    $sftpExportFolderTmp = $sftpExportPath . '/tmp';

    if (!is_dir($sftpExportFolderTmp)) {
        echo "Could not find tmp dir in SFTP export folder\n";

        return false;
    }

    $dirHandle = opendir($localFolder);

    if (!$dirHandle) {
        echo "Could not open export directory: $localFolder\n";
    }

    while (false != ($entry = readdir($dirHandle))) {

        if ($entry == '.' || $entry == '..') {
            continue;
        }

        $fileName = $localFolder . '/' . $entry;
        $sftpFileName = $sftpExportFolderTmp . '/' . $entry;

        echo "Copying file: $fileName to SFTP export folder ... ";

        $fileSize = filesize($fileName);

        $remoteFile = fopen($sftpFileName, 'w');
        $localFile = fopen($fileName, 'r');
        $writtenBytes = stream_copy_to_stream($localFile, $remoteFile);

        fclose($remoteFile);
        fclose($localFile);

        if ($writtenBytes !== $fileSize) {
            echo "Error: byte count does not match\n";

            continue;
        }

        //var_dump(ssh2_sftp_chmod($sftp, $sftpFileName, 0775));
        if (!rename($sftpFileName, $sftpExportPath . '/' . $entry)) {
            echo "Error: could not move export temp file \n";
            if (!ssh2_sftp_rename($handle, $sftpFolder . '/tmp/' . $entry, $sftpFolder . '/' . $entry)) {
                echo "Error: could not move export temp file using ssh2\n";
            } else {
                echo "OK\n";
            }
        } else {
            echo "OK\n";
        }

        if (!unlink($fileName)) {
            echo "Error: could not delete source file\n";
        }
    }

    return true;
}

function processImport($handle, $params)
{
    $localFolder = $params['localFolder'];
    $sftpFolder = $params['sftpFolder'];

    if (!is_dir($localFolder)) {
        echo "Import folder does not exist: $localFolder\n";

        return false;
    }

    $localFolderFolderTmp = $localFolder . '/tmp';

    if (!is_dir($localFolderFolderTmp)) {
        if (!mkdir($localFolderFolderTmp) || !is_dir($localFolderFolderTmp)) {
            echo "Could not create tmp dir in $localFolder\n";

            return false;
        }
    }

    $sftpResourceId = intval($handle);
    $dirHandle = opendir('ssh2.sftp://' . $sftpResourceId . '/' . $sftpFolder);

    if (!$dirHandle) {
        echo "Could not open sftp import directory: $sftpFolder\n";

        return false;
    }

    while (false != ($entry = readdir($dirHandle))) {

        if ($entry == '.' || $entry == '..') {
            continue;
        }

        $sftpFileName = 'ssh2.sftp://' . $sftpResourceId . '/' . $sftpFolder . '/' . $entry;

        if (is_dir($sftpFileName)) {
            continue;
        }

        echo "Copying file: $entry to $localFolderFolderTmp ... ";

        $stat = ssh2_sftp_stat($handle, $sftpFolder . '/' . $entry);

        $remoteFile = fopen($sftpFileName, 'r');
        $localFile = fopen($localFolderFolderTmp . '/' . $entry , 'w');
        $writtenBytes = stream_copy_to_stream($remoteFile, $localFile);

        fclose($remoteFile);
        fclose($localFile);

        if ($writtenBytes !== $stat['size']) {
            echo "Error: byte count does not match\n";

            continue;
        }

        if (!rename($localFolderFolderTmp . '/' . $entry, $localFolder . '/' . $entry)) {
            echo "Error: could not move import temp file\n";

        } else {
            echo "OK\n";
        }

        if (!unlink($sftpFileName)) {
            echo "Error: could not delete SFTP source file\n";
        }
    }

    return true;
}

function getHandle($protocol, $ini)
{
    $host = $ini['host'];
    $port = $ini['port'];
    $password = $ini['password'] ?: false;
    $isFTP = $protocol == 'FTP';

    if ($isFTP) {
        $cn = ftp_connect($host, $port);
    } else {
        $cn = ssh2_connect($host, $port);
    }

    if (!$cn) {
        echo "$protocol connect error\n";

        return null;
    }

    if ($isFTP) {
        $result = ftp_login($cn, $ini['login'], $password);
        if (!$result) {
            echo "FTP login error\n";

            return null;
        }

        return $cn;
    } else {
        $result = $password
            ? ssh2_auth_password ($cn, $ini['login'], $password)
            : ssh2_auth_pubkey_file($cn, $ini['login'], $ini['publicKeyPath'], $ini['privateKeyPath']);

        if (!$result) {
            echo "SSH login error\n";

            return null;
        }

        $sftp = ssh2_sftp($cn);

        if (!$sftp) {
            echo "Cannot start SFTP session\n";

            return null;
        }

        return $sftp;
    }
}

function processLFTP($ini, $export, $import)
{
    $host = $ini['host'];
    $port = $ini['port'];
    if ($port) {
        $host .= ':' . $port;
    }
    $user = $ini['login'];
    $password = $ini['password'] ?: false;

    if (!$password) {
        echo "No password is set for LFTP connection\n";

        return null;
    }

    $mirror = '';
    //--Remove-source-files
    foreach($export as $params) {
        $localFolder = $params['localFolder'];
        $remoteFolder = $params['sftpFolder'];
        $mirror .= "mirror --reverse --verbose --Remove-source-files $localFolder $remoteFolder\n";
    }
    foreach($import as $params) {
        $localFolder = $params['localFolder'];
        $remoteFolder = $params['sftpFolder'];
        $mirror .= "mirror --verbose --Remove-source-files $localFolder $remoteFolder\n";
    }

    $cmd = ''
        . "lftp <<EOF 2>&1\n"
        . "set net:timeout 5\n"
        . "set net:connection-limit 5\n"
        . "set net:max-retries 2\n"
        . "set bmk:save-passwords false\n"
        . "set cmd:fail-exit true\n"
        . "set cmd:interactive false\n"
        . "set cache:enable false\n"
        . "set dns:cache-enable false\n"
        . "set ftp:passive-mode true\n"
        . "set ftp:ssl-allow true\n"
        . "set mirror:parallel-directories false\n"
        . "set mirror:set-permissions true\n"
        . "set mirror:parallel-transfer-count 2\n"
        . "set mirror:dereference false\n"
        . "set ssl:verify-certificate false\n"
        . "open $host\n"
        . "user $user $password\n"
        . "$mirror"
        . "bye\n"
        . "EOF\n";

    @ob_start();

    $exit = 0;
    $result = @system($cmd, $exit);
    $out = @ob_get_clean();
    if ($result === false) {
        echo "LFTP Error code: $exit\n";
    }

    echo $out;
}
?>
