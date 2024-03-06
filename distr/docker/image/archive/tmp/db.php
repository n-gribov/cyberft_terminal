<?php

$dbh = new PDO('oci:dbname=CFT1;charset=utf8;', 'grigoryev', '123456');
foreach($dbh->query('SELECT 1 from dual') as $row) {
    print_r($row);
}
