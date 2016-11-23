<?php

error_reporting(E_ALL);
include_once("../util/dbConn.php");
include_once("../../util/usertoken.php");
$ut = $_POST['token'];
$sip = $_POST['sessionip'];
$ut = '26eb1f9f-127c-11e6-8d77-3417ebe60e97';
$sip = $_SERVER['REMOTE_ADDR'];
echo $ut . $sip;

$uu = new userUtils;
$db = new dbUtils;
$conn = $db->dbOpen();
$username = $uu->finduserfromtoken($ut, $sip, $conn);
$db->dbclose($conn);
echo ($username);
//hello

?>
        