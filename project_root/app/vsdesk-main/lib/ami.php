<?php

ini_set('display_errors', 0);
define('AC_DB_CS', 'mysql:host=localhost;port=3306;dbname=asteriskcdrdb'); //provide connection string
define('AC_DB_UNAME', 'freepbxuser'); //provide username to access asterisk DB
define('AC_DB_UPASS', 'freepbxpassword'); //provide password to access asterisk DB
define('AC_RECORD_PATH', 'https://localhost/records/%Y/%m/%d/#'); //provide path to records in filesystem

$db_cs = AC_DB_CS;
$db_u = empty(AC_DB_UNAME) ? null : AC_DB_UNAME;
$db_p = empty(AC_DB_UPASS) ? null : AC_DB_UPASS;

if (!empty($_GET['GETFILE'])) {
    $p = AC_RECORD_PATH;
    try {
        $dbh = new PDO($db_cs, $db_u, $db_p);
        $sth = $dbh->prepare('SELECT calldate, recordingfile FROM cdr WHERE uniqueid= :uid order by calldate DESC limit 1');
        $sth->bindValue(':uid', (string)$_GET['GETFILE']);
        $sth->execute();
        $r = $sth->fetch(PDO::FETCH_ASSOC);
        if ($r === false || empty($r['recordingfile'])) {
            die('Error while getting file from asterisk (no filename in select)');
        }
        $date = strtotime($r['calldate']);
        $replace = [];
        $replace['#'] = $r['recordingfile'];
        $dates = ['d', 'm', 'Y', 'y'];
        foreach ($dates as $d) {
            $replace['%' . $d] = date($d, $date);
        }
        $p = str_replace(array_keys($replace), array_values($replace), $p);
        if (empty($_GET['noredirect'])) {
            header('Location: ' . $p);
        }
        die($p);
    } catch (PDOException $e) {
        die('Error while getting file from asterisk');
    }
}
