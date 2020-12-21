<?php
/*
$conn = oci_connect('smithj', 'pwd4smithj', 'localhost/XE');


if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}*/

$dbtns = "//localhost:1521/xe";
$db_username = "smithj";
$db_password = "pwd4smithj";

$dbh = new PDO("oci:dbname=" . $dbtns . ";charset=utf8", $db_username, $db_password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

$res = $dbh->query("SELECT 1 FROM DUAL")->fetchAll();

var_dump($res );