<?php
/*
 * Скрипт для gitlab, удаляет все environments до $numfiles
 * access token - AeZ1jRMqBpzvQSynzHeu
 */


$numfiles = 409;
for ($k = 14; $k < $numfiles; $k++) {
        $command =  ('/usr/bin/curl --request DELETE --header "PRIVATE-TOKEN: AeZ1jRMqBpzvQSynzHeu" http://gitlab-new.cyberplat.com/api/v4/projects/5/environments/' . $k);
exec($command);
print_r($command);
};


