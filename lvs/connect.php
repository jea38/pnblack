<?php
ob_start();
session_start();

$dbhost 	= "pnblack-server.mysql.database.azure.com:3306";
$dbuser 	= "npjqjzlquh";
$dbpass 	= "mk9e22L4PcfzXV$U";
$dbname 	= "pnblack-database";
$charset 	= "utf8";

$dbcon = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$dbcon) {
    die("Connection failed" . mysqli_connect_error());
}
mysqli_select_db($dbcon,$dbname);
mysqli_set_charset($dbcon,$charset);
